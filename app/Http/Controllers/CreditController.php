<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Credit;
use App\Models\Notification;
use App\Models\StudentClass;
use App\Models\Payment;
use App\Models\Petugas;
use App\Models\Year;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $activeYearId = Year::where('year_current', 'selected')->value('id');

        $credit = Payment::orderBy("user_id", "DESC")
                    ->where('status','Pending')
                    ->where('year_id', $activeYearId)
                    ->get();

        $petugas = Petugas::orderBy("updated_at", "DESC")->get();
        $years = Year::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();

        return view('credit.index', compact('petugas','students', 'notifications', 'studentClasses','credit','years'));
    }


    public function addPage()
    {
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentSide = StudentClass::orderBy("class_name", 'ASC')->get();
        $students = User::orderBy('name')->get();
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId)->year_semester;
        $credits = Credit::where('semester', $years)->orderBy("updated_at", "DESC")->get();
        return view('credit.add', compact('students', 'studentSide', 'notifications', 'credits', 'years'));
    }

    public function storeSPP(Request $request)
    {
        $credits = $request->input('credit_id');

        $activeYearId = Year::where('year_status', 'active')->value('id');

        // Mendapatkan invoice number terakhir untuk SPP bulan ini
        $lastInvoiceNumber = Payment::where('type', 'SPP')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->orderBy('created_at', 'desc')
                                    ->value('invoice_number');

        // Mengambil increment dari invoice number terakhir
        $increment = 1;
        if ($lastInvoiceNumber) {
            $lastInvoiceNumberParts = explode('-', $lastInvoiceNumber);
            $lastIncrement = intval(end($lastInvoiceNumberParts));
            $increment = $lastIncrement + 1;
        }

        // Format tanggal hari ini dalam format "ddMMyy"
        $todayDate = Carbon::now()->format('dmy');

        // Buat invoice number
        $invoiceNumber = "SPP-$todayDate-$increment";

        foreach($credits as $credit)
        {
            $creditPrice = Credit::find($credit)->credit_price;
            Payment::create([
                'user_id' =>  $request->input('user_id'),
                'credit_id' => $credit,
                'year_id' => $activeYearId,
                'type' => $request->input('type'),
                'price' => $creditPrice,
                'invoice_number' => 0
            ]);
        }

        return response()->json([
            'message' => 'Data berhasil dibuat!'
        ], 201);
    }

    public function generateCredit()
    {
        $activeYearId = Year::where('year_current', 'selected')->value('id');

        $categories = Category::pluck('id');

        foreach ($categories as $category) {
            $credits = DB::table('category_has_credit')->where('category_id', $category)->pluck('credit_id');
            foreach($credits as $credit){
                $users = User::where('category_id', $category)->pluck('id');
                foreach($users as $user){
                    $creditPrice = Credit::find($credit)->credit_price;
                    // Cari pembayaran yang sesuai dengan kriteria yang diberikan
                    $payment = Payment::firstOrNew([
                        'user_id' => $user,
                        'credit_id' => $credit,
                        'year_id' => $activeYearId,
                    ]);

                    // Jika pembayaran belum ada, setel nilai atributnya dan simpan ke database
                    if (!$payment->exists) {
                        $payment->type = 'SPP';
                        $payment->price = $creditPrice;
                        $payment->increment = 0;
                        $payment->save();
                    }
                }
            } 
        }

        foreach ($categories as $category) {
            $attributes = DB::table('category_has_attribute')->where('category_id', $category)->pluck('attribute_id');
            foreach($attributes as $attribute){
                $users = User::where('category_id', $category)->pluck('id');
                foreach($users as $user){
                    $attributePrice = Attribute::find($attribute)->attribute_price;
                    // Cari pembayaran yang sesuai dengan kriteria yang diberikan
                    $payment = Payment::firstOrNew([
                        'user_id' => $user,
                        'attribute_id' => $attribute,
                        'year_id' => $activeYearId,
                    ]);

                    // Jika pembayaran belum ada, setel nilai atributnya dan simpan ke database
                    if (!$payment->exists) {
                        $payment->type = 'Daftar Ulang';
                        $payment->price = $attributePrice;
                        $payment->increment = 0;
                        $payment->save();
                    }
                }
            } 
        }

        return response()->json(['message' => 'Data paket berhasil dihapus.']);
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
                $query->select( 'status', 'payments.price');
            }])
            ->get();

        $studentSide = StudentClass::orderBy("class_name", 'ASC')->get();

        $class = StudentClass::find($id);


        return view('credit.detail', compact('studentSide', 'notifications', 'students', 'class'));
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

        $creditName = $request->input('credit_name');

        $slug = Str::slug($creditName);

        $credit = Credit::create([
            'slug' => $slug,
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

        $creditName = $request->input('credit_name');

        $slug = Str::slug($creditName);

        $credit->update([
            'credit_name' => $request->input('credit_name'),
            'credit_price' => $request->input('credit_price'),
            'semester' => $request->input('semester'),
            'slug' => $slug
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

        dd($order);

        $price = DB::table('payments')
                    ->join('credits', 'payments.credit_id', '=', 'credits.id')
                    ->select('credits.credit_price')
                    ->where('payments.id',$id)
                    ->first();
            
        $priceItem = $price->credit_price;

        $order->update([
            'price' => $priceItem,
            'status' => 'Pending'
        ]);

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = true;
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

    public function InvoicePage($uuid)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $credit = Payment::orderBy("updated_at", "DESC")
            ->where('uuid', $uuid)
            ->first();

        $credits = Payment::orderBy("updated_at", "DESC")
            ->where('uuid', $uuid)
            ->get();

        $tax = 5000;

        $priceCredits = Payment::orderBy("updated_at", "DESC")
            ->where('uuid', $uuid)
            ->sum('price');

        $totalPriceCredits = $tax + $priceCredits;

        $years = Year::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = true;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $totalPriceCredits,
            ),
            'customer_details' => array(
                'first_name' => $credit->user->name,
                'email' => $credit->user->email,
                'phone' =>  $credit->user->number,
            ),
        );
        
            $snapToken = \Midtrans\Snap::getSnapToken($params);
    
            return view('payment.midtrans.invoice', compact('snapToken','totalPriceCredits', 'students', 'credits', 'notifications', 'studentClasses', 'credit', 'years'));
      
    }

}
