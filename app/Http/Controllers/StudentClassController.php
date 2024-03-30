<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Student;
use App\Models\Year;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

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
        $classes = StudentClass::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();

        return view('setting.class.index', compact("students","notifications", "classes"));
    }
    public function store(Request $request)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        // Membuat UUID
        $uuid = Uuid::uuid4()->toString();

        $classes = StudentClass::create([
            'uuid' => $uuid,
            'class_name' => $request->input('class_name')
        ]);

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data kelas" . " " . $request->input('class_name') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data kelas berhasil disimpan!',
            'data' => $classes,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $class = StudentClass::find($id);

        $class->update([
            "class_name" =>  $request->input('class_name')
        ]);
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "mengedit data kelas" . " " . $class->class_name  . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data kelas berhasil diedit!',
            'data' => $class,
        ], 201);
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
            'notification_content' => Auth::user()->name . " " . "menghapus data kelas" . " " . $classes->class_name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data kelas berhasil dihapus.']);
    }
}
