<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\StudentClass;

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
        foreach ($users as $user) {
            $user->class_id = $request->input('class_id');
            $user->save();
        }

        return response()->json([
            'message' => 'Data updated successfully'
        ], 201); // 201 updated
    }

    public function destroyAllStudent($id)
    {
        User::where('class_id', $id)
            ->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ], 201);
    }
}
