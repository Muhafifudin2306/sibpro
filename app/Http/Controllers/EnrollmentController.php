<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use App\Models\StudentClass;

class EnrollmentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();

        return view('enrollment.index', compact('notifications', 'studentClasses'));
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
            ->with(['enrollments' => function ($query) {
                $query->select('attributes.id', 'attribute_name', 'status', 'user_has_attribute.attribute_price', 'user_has_attribute.id as user_attribute_id');
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

        $student = User::find($id);

        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();

        return view('enrollment.billing', compact('notifications', 'student'));
    }
}
