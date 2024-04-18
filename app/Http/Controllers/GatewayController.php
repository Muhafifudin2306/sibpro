<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;

class GatewayController extends Controller
{

    var $apiInstance = NULL;
    public function __construct()
    {
        Configuration::setXenditKey('xnd_development_1pScqsY0tV43bIulF7jaScjjwcWOSBqIRaAajij5f5fKw6AmSP75fox3z17tdX');
        $this->apiInstance = new InvoiceApi();
    }

    public function confirmPaymentOnline(Request $request)
    {
        // Ambil invoice number terakhir
        $lastInvoiceNumber = Payment::whereYear('updated_at', Carbon::now()->year)
                                        ->whereMonth('updated_at', Carbon::now()->month)
                                        ->orderBy('updated_at', 'DESC')
                                        ->max('increment');

        // dd($lastInvoiceNumber);

        $increment = 1;
        if ($lastInvoiceNumber != NULL) {
        $increment = $lastInvoiceNumber + 1;
        }

        // Format tanggal hari ini dalam format "ddMMyy"
        $todayDate = Carbon::now()->format('dmy');

        // Buat invoice number baru
        $invoiceNumber = 'PAY'. '-' . $todayDate . '-' . $increment;
        $transactionIds = $request->input('transactions');

        // Mengambil total harga pembayaran dari beberapa ID yang diberikan
        $amount = Payment::whereIn('id', $transactionIds)->sum('price');

        $fee = 4500; 
        
        $uuid = Str::uuid(); // Generate UUID
        $totalAmount = $fee + $amount; // Menghitung total amount
        $successRedirectUrl = route('paymentXendit', ['uuid' => $uuid]);
        
        $create_invoice_request = new \Xendit\Invoice\CreateInvoiceRequest([
            'external_id' => (string) Str::uuid(),
            'description' => 'Checkout Pembayaran Kwitansi'. ' ' . $invoiceNumber, 
            'amount' => $totalAmount,
            'success_redirect_url' =>  $successRedirectUrl
        ]);
        

        $createInvoice = $this->apiInstance->createInvoice($create_invoice_request);

        // Validasi request
        $request->validate([
            'transactions' => 'required|array',
            'transactions.*' => 'exists:payments,id' // Pastikan transaksi tersedia dalam database
        ]);

        $transactionIds = $request->input('transactions');
        // Perbarui status pembayaran untuk transaksi yang dipilih
        Payment::whereIn('id', $transactionIds)->update([
            'checkout_link' => $createInvoice['invoice_url'],
            'uuid' => $uuid,
            'invoice_number' => $invoiceNumber,
            'increment' => $increment,
            'status'   => 'Pending',
            'payment_type' => 'Online',
            'external_id' => $create_invoice_request['external_id']
        ]);
    
        // Kembalikan respons sukses
        return response()->json(['message' => 'Pembayaran online berhasil dilakukan'], 200);
    }

    // public function callback(Request $request)
    // {
    //     $serverKey = config('midtrans.server_key');

    //     if (!empty($request->order_id) && !empty($request->status_code) && !empty($request->gross_amount)) {

    //         $hashed = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

    //         if ($hashed == $request->signature_key) {
    //             if ($request->transaction_status == 'settlement') {
    //                 Payment::where("invoice_number",'=',$request->order_id)->update([
    //                     'status' => 'Paid'
    //                 ]);
    //                 \Log::info('Pembayaran berhasil. ID Pesanan: ' . $request->order_id);
    //             }
    //             \Log::info('Callback Midtrans berhasil diproses.');
    //         } else {
    //             \Log::warning('Hash tidak cocok. Ada potensi perubahan data tidak sah.');
    //         }
    //     } else {
    //         \Log::error('Data callback tidak lengkap atau tidak valid.');
    //     }
    // }
}
