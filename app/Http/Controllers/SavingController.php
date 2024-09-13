<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Payment;
use App\Models\StudentClass;
use App\Models\Year;
use Illuminate\Http\Request;

class SavingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($uuid) {
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $classId = StudentClass::where('uuid', $uuid)->first()->id;
    
        $savings = Payment::orderBy("user_id", "DESC")
                    ->where('year_id', $activeYearId)
                    ->whereHas('user', function ($query) use ($classId) {
                        $query->where('class_id', $classId);
                    })
                    ->where('credit_id', '!=', null)
                    ->get()
                    ->groupBy('user_id'); // Mengelompokkan berdasarkan user_id
        
        // Data lainnya
        $years = Year::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();
    
        return view('savings.read', compact('savings', 'notifications', 'studentClasses', 'students', 'years'));
    }
    
}
