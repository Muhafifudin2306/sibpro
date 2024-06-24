<?php

namespace App\Http\Controllers;

use App\Models\Attribute as ModelsAttribute;
use App\Models\Credit;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use App\Models\StudentClass;
use App\Models\Payment;
use App\Models\Year;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    // public function index()
    // {
    //     $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
    //     $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();

    //     return view('enrollment.index', compact('notifications', 'studentClasses'));
    // }

    public function index($id)
    {
        $activeYearId = Year::where('year_current', 'selected')->value('id');

        $credit = Payment::orderBy("user_id", "DESC")
            ->where('year_id', $activeYearId)
            ->whereHas('user', function ($query) use ($id) {
                $query->where('class_id', $id);
            })
            ->where('status', 'Unpaid')
            ->get();
        $years = Year::select('year_name', 'year_current')->orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();
        $studentsList = User::with('roles', 'categories')
            ->whereHas('roles', function ($query) use ($id) {
                $query->where('id', 2)->where('class_id', $id);
            })
            ->select('uuid', 'name', 'email', 'user_email', 'number', 'nis', 'id', 'class_id', 'category_id', 'gender')
            ->orderBy("updated_at", "DESC")
            ->get();
        $creditList = Credit::all();
        $attributeList = ModelsAttribute::all();

        return view('enrollment.index', compact('students', 'notifications', 'studentClasses', 'credit', 'years', 'studentsList', 'creditList', 'attributeList'));
    }

    public function detail($uuid)
    {
        $data = StudentClass::where('uuid', $uuid)->first();

        if (!$data) {
            // Handle jika data tidak ditemukan
            abort(404);
        }

        $id = $data->id;

        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $students = User::where('class_id', $id)
            ->whereNotNull('category_id')
            ->with(['paymentAttribute' => function ($query) {
                $query->select('attributes.id', 'attribute_name', 'attribute_price as total_price', 'status', 'payments.price');
            }])
            ->get();


        $class = StudentClass::find($id);


        return view('enrollment.detail', compact('notifications', 'students', 'class'));
    }

    public function billingStudent($uuid)
    {
        $data = User::where('uuid', $uuid)->first();

        if (!$data) {
            // Handle jika data tidak ditemukan
            abort(404);
        }

        $id = $data->id;

        $student = DB::table('payments')
            ->join('users', 'payments.user_id', '=', 'users.id')
            ->join('attributes', 'payments.attribute_id', '=', 'attributes.id')
            ->select('attributes.attribute_name', 'attributes.attribute_price', 'payments.status', 'payments.id')
            ->where('user_id', $id)
            ->get();

        $user = User::find($id);


        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();

        return view('enrollment.billing', compact('notifications', 'student', 'user'));
    }

    private function generateInvoiceNumberEnrollment()
    {
        $digits = mt_rand(1000000, 9999999);

        $letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);

        $invoiceNumber = "DU" . "-" . $digits . "-" . $letters;

        return $invoiceNumber;
    }

    public function processMultiplePayments(Request $request)
    {
        $transactionIds = $request->input('attribute_id');
        $transactions = [];

        $invoiceNumber = $this->generateInvoiceNumberEnrollment();

        foreach ($transactionIds as $transactionId) {
            $payment = Payment::find($transactionId);

            $price = DB::table('payments')
                ->join('attributes', 'payments.attribute_id', '=', 'attributes.id')
                ->select('attributes.attribute_price')
                ->where('payments.id', $transactionId)
                ->first();

            $priceItem = $price->attribute_price;

            $payment->update(['invoice_number' => $invoiceNumber, 'price' => $priceItem, 'status' => 'Pending']);
        }
        // Validate if the user is authorized to perform these transactions (optional)

        // Now process the selected transactions
        foreach ($transactionIds as $transactionId) {
            $transaction = Payment::find($transactionId);

            // Add the transaction to the array
            $transactions[] = $transaction;
        }

        // Calculate total amount for the group payment
        $totalAmount = collect($transactions)->sum(function ($transaction) {
            return $transaction->attribute->attribute_price;
        });

        // Set up Midtrans configuration outside the loop
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Create a unique order ID for the group payment
        $orderId = 'GROUP_' . now()->format('YmdHis') . $transactions[0]->id;

        // Prepare Midtrans parameters for the group payment
        $params = [
            'transaction_details' => [
                'order_id' => $transaction->invoice_number,
                'gross_amount' => $totalAmount,
            ],
            'customer_details' => [
                'name' => $transactions[0]->user->name, // Assuming all transactions belong to the same user
                'email' => $transactions[0]->user->email,
                'phone' => $transactions[0]->user->nis,
            ],
        ];

        foreach ($transactions as $transaction) {
            $params['item_details'][] = [
                'id' => $transaction->id,
                'name' => $transaction->attribute->attribute_name,
                'price' => $transaction->attribute->attribute_price,
                'quantity' => 1,
            ];
        }

        // Get Snap token for the group payment
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();

        return view('enrollment.payment', compact('transactions', 'snapToken', 'notifications'));
    }

    public function destroy($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Data Pembayran tidak ditemukan.'], 404);
        }

        $payment->delete();

        return response()->json(['message' => 'Data pembayaran berhasil dihapus!']);
    }

    public function editData(Request $request, $id)
    {
        $payment = Payment::find($id);

        $payment->update([
            'price' => $request->input('price'),
            'status' => $request->input('status')
        ]);
        $inputStatus = $request->input('status');

        if ($inputStatus == 'Paid') {
            $payment->update([
                'payment_type' => 'Khusus',
                'petugas_id' => 1,
                'invoice_number' => '-'
            ]);
        }

        return response()->json([
            'message' => 'Data updated successfully',
            'data' => $payment,
        ], 201);
    }
}
