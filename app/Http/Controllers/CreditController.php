<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credit;
use App\Models\Notification;
use App\Models\StudentClass;
use App\Models\Student;
use App\Models\Year;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    public function __construct()
    {
        // Tambahkan middleware autentikasi ke metode 'store'
        $this->middleware('auth')->only('store');
    }

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
        $students = Student::where('class_id', $id)->whereNotNull('category_id')->with(['credits' => function ($query) {
            $query->select('credits.id', 'credit_name', 'status', 'student_has_credit.credit_price');
        }])->get();
        $class = StudentClass::find($id);

        return view('credit.detail', compact('notifications', 'students', 'class'));
        // dd($students);
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
}
