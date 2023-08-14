<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Year;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $students = Student::where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();

        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('setting.student.index', compact('notifications', 'students'));
    }
}
