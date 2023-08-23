<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Year;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;

class StudentClassController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        // Filter data kelas berdasarkan ID tahun aktif
        $classes = StudentClass::orderBy("updated_at", "DESC")->get();

        return view('setting.class.index', compact("notifications", "classes"));
    }
    public function store(Request $request)
    {
        // Dapatkan ID tahun aktif dari tabel Year
        $activeYearId = Year::where('year_status', 'active')->value('id');

        // Simpan data kelas untuk tahun aktif
        $classes = StudentClass::create([
            'class_name' => $request->input('class_name'),
            'user_id' => Auth::user()->id
        ]);

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Membuat Data Kelas" . " " . $request->input('class_name') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $classes,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $class = StudentClass::find($id);

        $class->update([
            "class_name" =>  $request->input('class_name'),
            'user_id' => Auth::user()->id
        ]);
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Mengedit Data Kelas" . " " . $class->class_name  . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $class,
        ], 201); // 201 updated
    }

    public function destroy($id)
    {
        $classes = StudentClass::find($id);

        if (!$classes) {
            return response()->json(['message' => 'Data Kelas tidak ditemukan.'], 404);
        }

        $classes->delete();
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Menghapus Data Kelas" . " " . $classes->class_name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_staus' => 0
        ]);

        return response()->json(['message' => 'Data Kelas berhasil dihapus.']);
    }
}
