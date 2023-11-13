<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Year;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();

        return view('setting.student.index', compact('notifications', 'studentClasses'));
    }

    public function detail($id)
    {
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $students = User::where('class_id', $id)->get();
        $class = StudentClass::find($id);
        $classes = StudentClass::orderBy("updated_at", "DESC")->get();

        return view('setting.student.detail', compact('notifications', 'students', 'class', 'classes'));
    }

    public function updateAllClass(Request $request, $id)
    {
        $users = User::where('class_id', $id)->get();

        $class = StudentClass::find($id);

        foreach ($users as $user) {
            $user->class_id = $request->input('class_id');
            $user->save();
        }

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "mengedit semua siswa kelas" . " " . $class->class_name . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data updated successfully'
        ], 201);
    }

    public function destroyAllStudent($id)
    {
        $class = StudentClass::find($id);

        User::where('class_id', $id)
            ->delete();

            $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menghapus semua siswa kelas" . " " . $class->class_name . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data deleted successfully'
        ], 201);
    }
}
