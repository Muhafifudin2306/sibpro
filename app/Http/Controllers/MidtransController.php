<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\UserHasCredit;

class MidtransController extends Controller
{
    public function payment($uuid)
    {
        $data = UserHasCredit::where('uuid', $uuid)->first();

        if (!$data) {
            // Handle jika data tidak ditemukan
            abort(404);
        }

        $id = $data->id;
        
        $order = UserHasCredit::find($id);
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $id,
                'gross_amount' => $order->credit->credit_price,
            ),
            'customer_details' => array(
                'name' => $order->user->name,
                'email' => $order->user->email,
                'phone' =>  $order->user->nis,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        return view('credit.payment', compact('order', 'snapToken', 'notifications'));
    }

    public function callback(Request $request)
    {
        // Ambil nilai server key dari konfigurasi
        $serverKey = config('midtrans.server_key');

        // Pastikan bahwa semua field yang dibutuhkan ada dan tidak kosong
        if (!empty($request->order_id) && !empty($request->status_code) && !empty($request->gross_amount)) {

            // Hitung hash dengan menggunakan algoritma SHA512
            $hashed = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

            // Periksa apakah hash yang dihasilkan sama dengan signature_key dari Midtrans
            if ($hashed == $request->signature_key) {

                // Jika status transaksi adalah 'settlement', tandai pesanan sebagai 'Paid'
                if ($request->transaction_status == 'settlement') {
                    $order = UserHasCredit::find($request->order_id);

                    // Update status dan harga kredit
                    $order->update([
                        'status' => 'Paid',
                        'credit_price' => $request->gross_amount
                    ]);

                    // Tambahan: Log ke file jika diperlukan
                    \Log::info('Pembayaran berhasil. ID Pesanan: ' . $request->order_id);
                }

                // Tambahan: Log ke file jika diperlukan
                \Log::info('Callback Midtrans berhasil diproses.');
            } else {
                // Tambahan: Log ke file jika diperlukan
                \Log::warning('Hash tidak cocok. Ada potensi perubahan data tidak sah.');
            }
        } else {
            // Tambahan: Log ke file jika diperlukan
            \Log::error('Data callback tidak lengkap atau tidak valid.');
        }
    }
}
