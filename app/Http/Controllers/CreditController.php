<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Credit;
use App\Models\Notification;
use App\Models\StudentClass;
use App\Models\Payment;
use App\Models\Year;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();

        return view('credit.index', compact('notifications', 'studentClasses'));
    }

    public function allData()
    {
        $credit = Payment::where('invoice_number','!=','')
                    ->whereHas('year', function ($query) {$query->where('id', '=', Year::where('year_current', 'selected')->value('id'));})
                    ->orderBy("updated_at", "DESC")
                    ->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();

        return view('credit.allData', compact('notifications', 'studentClasses','credit'));
    }

    public function detail($uuid)
    {
        $data = StudentClass::where('uuid', $uuid)->first();

        if (!$data) {
            abort(404);
        }

        $id = $data->id;

        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $students = User::where('class_id', $id)
            ->whereNotNull('category_id')
            ->with(['paymentCredit' => function ($query) {
                $query->select('credits.id', 'credit_name', 'status', 'payments.price');
            }])
            ->get();

        $class = StudentClass::find($id);


        return view('credit.detail', compact('notifications', 'students', 'class'));
    }

    public function billingStudent($uuid)
    {
        $data = User::where('uuid', $uuid)->first();

        if (!$data) {
            // Handle jika data tidak ditemukan
            abort(404);
        }

        $id = $data->id;

        $student = Payment::where('user_id','=',$id)
                            ->where('type','=',1)
                            ->get();

        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();

        return view('credit.billing', compact('notifications', 'student'));
    }

    public function store(Request $request)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $credit = Credit::create([
            'credit_name'   => $request->input('credit_name'),
            'credit_price'  => $request->input('credit_price'),
            'semester'      => $request->input('semester')
        ]);
        
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data atribut SPP" . " " . $request->input('credit_name') . " " . "dengan harga" . " " . "Rp" . $request->input('credit_price') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data atribut berhasil ditambah!',
            'data' => $credit,
        ], 201);
    }

    public function destroy($id)
    {
        $credit = Credit::find($id);

        if (!$credit) {
            return response()->json(['message' => 'Data Atribut tidak ditemukan.'], 404);
        }

        $credit->delete();

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menghapus data atribut SPP" . " " . $credit->credit_name . " " . "dengan harga" . " " . "Rp" . $credit->credit_price . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data atribut berhasil dihapus!']);
    }

    public function update(Request $request, $id)
    {
        $credit = Credit::find($id);

        $credit->update([
            'credit_name' => $request->input('credit_name'),
            'credit_price' => $request->input('credit_price'),
            'semester' => $request->input('semester'),
            'user_id' => Auth::user()->id
        ]);

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "mengedit data atribut SPP" . " " . $credit->credit_name . " " . "dengan harga" . " " . "Rp" .  $request->input('credit_price') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data atribut berhasil diedit!',
            'data' => $credit
        ], 201);
    }

    public function payment($uuid)
    {
        $data = Payment::where('uuid', $uuid)->first();

        if (!$data) {
            abort(404);
        }

        $id = $data->id;
        
        $order = Payment::find($id);

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $transactionDetails = array(
            'order_id' => $order->invoice_number,
            'gross_amount' => $order->credit->credit_price,
        );

        $customerDetails = array(
            'first_name' => $order->user->name,
            'email' => $order->user->email,
            'phone' =>  $order->user->nis,
        );

        $itemDetails = array(
            array(
                'id' => $id,
                'name' => $order->credit->credit_name,
                'quantity' => 1,
                'price' => $order->credit->credit_price,
            ),
        );

        $params = array(
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
        );

        // Menambahkan item_details secara terpisah
        $params['item_details'] = $itemDetails;


        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        return view('credit.payment', compact('order', 'snapToken', 'notifications'));
    }

}
