<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Year;
use App\Models\StudentClass;

class PaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $credit = Payment::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('payment.credit.index', compact('notifications', 'credit'));
    }

    public function allData()
    {
        $credit = Payment::where('status','!=','Unpaid')
                    ->whereHas('year', function ($query) {$query->where('id', '=', Year::where('year_current', 'selected')->value('id'));})
                    ->orderBy("updated_at", "DESC")
                    ->get();
        $years = Year::select('year_name','year_semester')->orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();

        return view('payment.allData', compact('notifications', 'studentClasses','credit','years'));
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
