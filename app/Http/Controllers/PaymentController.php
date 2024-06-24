<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Year;

use Illuminate\Support\Str;
use App\Models\StudentClass;
use Dompdf\Dompdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function indexCart()
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $credit = Payment::orderBy("user_id", "DESC")
            ->where('user_id', Auth::user()->id)
            ->where('year_id', $activeYearId)
            ->where('status', 'Unpaid')
            ->get();
        $years = Year::orderBy("updated_at", "DESC")->get();

        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();

        return view('payment.user.cart.index', compact('students', 'notifications', 'studentClasses', 'credit', 'years'));
    }

    public function indexPayment()
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $credit = Payment::where('user_id', Auth::user()->id)
            ->where('status', 'Pending')
            ->where('year_id', $activeYearId)
            ->orderBy('invoice_number', 'DESC')
            ->groupBy('invoice_number', 'payment_type', 'checkout_link', 'uuid', 'updated_at', 'status') // Mengelompokkan berdasarkan invoice_number dan payment_type
            ->select('invoice_number', 'uuid', 'payment_type', 'status', 'updated_at', DB::raw('SUM(price) as total_price'), 'checkout_link') // Memilih invoice_number dan payment_type
            ->get();


        $years = Year::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();

        $time_now = Carbon::now();


        return view('payment.user.payment.index', compact('students', 'notifications', 'studentClasses', 'credit', 'years', 'time_now'));
    }

    public function addToCart(Request $request)
    {
        // Validasi request
        $request->validate([
            'transactions' => 'required|array',
            'transactions.*' => 'exists:payments,id' // Pastikan transaksi tersedia dalam database
        ]);

        $transactionIds = $request->input('transactions');

        $uuid = Str::uuid(); // Generate UUID

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
        $invoiceNumber = 'PAY' . '-' . $todayDate . '-' . $increment;

        Payment::whereIn('id', $transactionIds)->update([
            'status' => 'Pending',
            'payment_type' => 'Offline',
            'uuid' => $uuid,
            'invoice_number' => $invoiceNumber,
            'increment' => $increment,
        ]);

        // Kembalikan respons sukses
        return response()->json(['message' => 'Pembayaran online berhasil dilakukan'], 200);
    }

    public function indexPaymentDone()
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $credit = Payment::orderBy("updated_at", "DESC")
            ->where('user_id', Auth::user()->id)
            ->where('status', 'Paid')
            ->get();
        $years = Year::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();


        return view('payment.user.payment.done', compact('students', 'notifications', 'studentClasses', 'credit', 'years'));
    }

    public function detailPaymentDone($id)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $credit = Payment::orderBy("updated_at", "DESC")
            ->where('id', $id)
            ->first();

        $credits = Payment::orderBy("updated_at", "DESC")
            ->where('id', $id)
            ->get();

        $totalPriceCredits = Payment::orderBy("updated_at", "DESC")
            ->where('id', $id)
            ->sum('price');

        $years = Year::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();

        if (is_null($credit) || is_null($activeYearId) || is_null($credits) || is_null($credit->petugas) || is_null($totalPriceCredits)) {
            return redirect('/payment-done');
        }

        return view('payment.user.payment.detail', compact('totalPriceCredits', 'students', 'credits', 'notifications', 'studentClasses', 'credit', 'years'));
    }

    public function detailKwitansiDone($invoice_number)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $credit = Payment::orderBy("updated_at", "DESC")
            ->where('invoice_number', $invoice_number)
            ->first();

        $credits = Payment::orderBy("updated_at", "DESC")
            ->where('invoice_number', $invoice_number)
            ->get();

        $totalPriceCredits = Payment::orderBy("updated_at", "DESC")
            ->where('invoice_number', $invoice_number)
            ->sum('price');

        $years = Year::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();

        // return dd($credits);
        $allPaid = $credits->every(fn ($credits) => $credits['status'] === 'Paid');

        return view('payment.user.payment.detailKwitansi', compact('totalPriceCredits', 'students', 'credits', 'notifications', 'studentClasses', 'credit', 'years', 'allPaid'));
    }

    public function printPaymentDone($id)
    {
        $credit = Payment::orderBy("updated_at", "DESC")
            ->where('id', $id)
            ->first();

        $credits = Payment::orderBy("updated_at", "DESC")
            ->where('id', $id)
            ->get();

        $totalPriceCredits = Payment::orderBy("updated_at", "DESC")
            ->where('id', $id)
            ->sum('price');

        $data = [
            'credit' => $credit,
            'credits' => $credits,
            'totalPriceCredits' => $totalPriceCredits,
        ];

        $pdf = new Dompdf();
        $pdf->loadHtml(view('payment.user.payment.print', $data));

        $pdf->setPaper('A4', 'landscape');

        $pdf->render();
        return $pdf->stream('Kwitansi_Pembayaran' . '_' . $credit->invoice_number . '.pdf');
    }

    public function printKwitansiDone($invoice_number)
    {
        $credit = Payment::orderBy("updated_at", "DESC")
            ->where('invoice_number', $invoice_number)
            ->first();

        $credits = Payment::orderBy("updated_at", "DESC")
            ->where('invoice_number', $invoice_number)
            ->get();

        $totalPriceCredits = Payment::orderBy("updated_at", "DESC")
            ->where('invoice_number', $invoice_number)
            ->sum('price');

        $data = [
            'credit' => $credit,
            'credits' => $credits,
            'totalPriceCredits' => $totalPriceCredits,
        ];

        $pdf = new Dompdf();
        $pdf->loadHtml(view('payment.user.payment.print', $data));

        $pdf->setPaper('A4', 'landscape');

        $pdf->render();
        return $pdf->stream('Kwitansi_Pembayaran' . '_' . $credit->invoice_number . '.pdf');
    }

    public function confirmPayment(Request $request)
    {
        // Validasi request
        $request->validate([
            'transactions' => 'required|array',
            'transactions.*' => 'exists:payments,id' // Pastikan transaksi tersedia dalam database
        ]);

        $transactionIds = $request->input('transactions');

        // Ambil invoice number terakhir
        $lastInvoiceNumber = Payment::whereYear('updated_at', Carbon::now()->year)
            ->whereMonth('updated_at', Carbon::now()->month)
            ->orderBy('updated_at', 'DESC')
            ->where('status', 'Paid')
            ->value('increment');

        // dd($lastInvoiceNumber);

        $increment = 1;
        if ($lastInvoiceNumber != NULL) {
            $increment = $lastInvoiceNumber + 1;
        }

        // Format tanggal hari ini dalam format "ddMMyy"
        $todayDate = Carbon::now()->format('dmy');

        // Buat invoice number baru
        $invoiceNumber = 'PAY' . '-' . $todayDate . '-' . $increment;
        $transactionIds = $request->input('transactions');
        $petugasId = $request->input('petugas_id');


        // Perbarui status pembayaran untuk transaksi yang dipilih
        Payment::whereIn('id', $transactionIds)->update([
            'status' => 'Paid',
            'increment' => $increment,
            'invoice_number' => $invoiceNumber,
            'petugas_id' => $petugasId
        ]);

        // Kembalikan respons sukses
        return response()->json(['message' => 'Pembayaran online berhasil dilakukan'], 200);
    }

    public function confirmXendit($uuid)
    {
        $payment = Payment::where('uuid', $uuid)->update([
            'status' => 'Paid',
            'petugas_id' => 1
        ]);

        return redirect('payment-done');
    }

    public function cancelPayment($uuid)
    {
        $payment = Payment::where('uuid', $uuid)->update([
            'status' => 'Unpaid',
            'uuid' => NULL,
            'increment' => NULL,
            'invoice_number' => NULL,
            'payment_type' => NULL,
            'checkout_link' => NULL,
            'external_id' => NULL,
            'petugas_id' => NULL
        ]);

        return redirect('payment');
    }

    public function rejectPayment($id)
    {
        $payment = Payment::find($id)->update([
            'status' => 'Unpaid',
            'uuid' => NULL,
            'increment' => NULL,
            'invoice_number' => NULL,
            'payment_type' => NULL,
            'checkout_link' => NULL,
            'external_id' => NULL,
            'petugas_id' => NULL
        ]);

        return response()->json(['message' => 'Pengajuan Pembayaran berhasil dihapus'], 200);
    }

    public function allData()
    {
        $activeYearId = Year::where('year_current', 'selected')->value('id');

        $credit = Payment::where('status', 'Paid')
            ->whereHas('year', function ($query) {
                $query->where('id', '=', Year::where('year_current', 'selected')->value('id'));
            })
            ->groupBy('invoice_number', 'user_id', 'status', 'petugas_id', 'updated_at', 'payment_type')
            ->select('invoice_number', 'user_id', 'status', 'petugas_id', 'updated_at', 'payment_type', DB::raw('SUM(price) as total_price'))
            ->orderBy("updated_at", "DESC")
            ->get();

        $years = Year::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();

        return view('payment.allData', compact('students', 'notifications', 'studentClasses', 'credit', 'years'));
    }

    public function allTransaction()
    {
        $credit = Payment::where('status', '!=', 'Unpaid')
            ->whereHas('year', function ($query) {
                $query->where('id', '=', Year::where('year_current', 'selected')->value('id'));
            })
            ->groupBy('invoice_number', 'user_id', 'status', 'petugas_id', 'updated_at', 'payment_type')
            ->select('invoice_number', 'user_id', 'status', 'petugas_id', 'updated_at', 'payment_type', DB::raw('SUM(price) as total_price'))
            ->orderBy("updated_at", "DESC")
            ->get();
        $years = Year::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();

        return view('payment.recentData', compact('students', 'notifications', 'studentClasses', 'credit', 'years'));
    }

    public function todayTransaction()
    {
        $currentDate = Carbon::now()->toDateString();

        $credit = Payment::where('status', 'Paid')
            ->whereHas('year', function ($query) {
                $query->where('id', '=', Year::where('year_current', 'selected')->value('id'));
            })
            ->groupBy('invoice_number', 'user_id', 'status', 'petugas_id', 'updated_at', 'payment_type')
            ->whereDate('updated_at', $currentDate)
            ->select('invoice_number', 'user_id', 'status', 'petugas_id', 'updated_at', 'payment_type', DB::raw('SUM(price) as total_price'))
            ->orderBy("updated_at", "DESC")
            ->get();

        $sumPrice = Payment::where('status', 'Paid')
            ->whereHas('year', function ($query) {
                $query->where('id', '=', Year::where('year_current', 'selected')->value('id'));
            })
            ->whereDate('updated_at', $currentDate)
            ->sum('price');
        $creditPrice = Payment::where('status', 'Paid')
            ->where('type', 'SPP')
            ->whereHas('year', function ($query) {
                $query->where('id', '=', Year::where('year_current', 'selected')->value('id'));
            })
            ->whereDate('updated_at', $currentDate)
            ->sum('price');

        $attributePrice = Payment::where('status', 'Paid')
            ->where('type', 'Daftar Ulang')
            ->whereHas('year', function ($query) {
                $query->where('id', '=', Year::where('year_current', 'selected')->value('id'));
            })
            ->whereDate('updated_at', $currentDate)
            ->sum('price');

        $years = Year::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();

        return view('payment.todayData', compact('students', 'notifications', 'studentClasses', 'credit', 'years', 'sumPrice', 'creditPrice', 'attributePrice'));
    }

    public function detail($id)
    {
        $order = Payment::find($id);
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
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
        return view('payment.credit.detail', compact('order', 'snapToken', 'notifications'));
    }
}
