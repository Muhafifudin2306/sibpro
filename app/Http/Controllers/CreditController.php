<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Credit;
use App\Models\Notification;
use App\Models\StudentClass;
use App\Models\UserHasCredit;
use App\Models\Year;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{

    public function index()
    {
        // Get Data Notification
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();

        return view('credit.index', compact('notifications', 'studentClasses'));
    }

    public function detail($id)
    {
        // Get Data Notification
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $students = User::where('class_id', $id)
            ->whereNotNull('category_id')
            ->with(['credits' => function ($query) {
                $query->select('credits.id', 'credit_name', 'status', 'user_has_credit.credit_price', 'user_has_credit.id as user_credit_id');
            }])
            ->get();

        $class = StudentClass::find($id);


        return view('credit.detail', compact('notifications', 'students', 'class'));
        //dd($students);
    }

    public function store(Request $request)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $credit = Credit::create([
            'credit_name'   => $request->input('credit_name'),
            'credit_price'  => $request->input('credit_price'),
            'semester'      => $request->input('semester'),
            'user_id'       => Auth::user()->id
        ]);
        // Create data notification
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Membuat Data Atribut" . " " . $request->input('credit_name') . " " . "dengan harga" . " " . "Rp" . $request->input('credit_price') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data inserted successfully',
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
            'notification_content' => Auth::user()->name . " " . "Menghapus Data Atribut" . " " . $credit->credit_name . " " . "dengan harga" . " " . "Rp" . $credit->credit_price . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_staus' => 0
        ]);

        return response()->json(['message' => 'Data Tahun berhasil dihapus.']);
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
            'notification_content' => Auth::user()->name . " " . "Mengedit Data Atribut" . " " . $credit->credit_name . " " . "dengan harga" . " " . "Rp" .  $request->input('credit_price') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data update successfully',
            'data' => $credit
        ], 201);
    }

    public function payment($id)
    {
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
                'order_id' => rand(),
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
        $serverKey = config('midtrans.server_key');

        $hashed = hash('sha512', $request->order_id.$request->status_code.$request->order_id.$request->gross_amount.$serverKey);

        if($hashed == $request->signature_key){
            if($request->transaction_status == 'settlement'){
                $order = UserHasCredit::find($request->order_id);

                $order->update([
                    'status' => 'Paid',
                    'credit_price' => $request->gross_amount
                ]);
            }
        }
    }
}
