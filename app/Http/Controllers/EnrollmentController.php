<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserHasCredit;
use App\Models\Notification;

class EnrollmentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $credit = UserHasCredit::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('payment.enrollment.index', compact('notifications', 'credit'));
    }
}
