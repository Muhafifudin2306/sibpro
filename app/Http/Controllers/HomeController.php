<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\ExternalIncome;
use App\Models\Spending;
use App\Models\User;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Year;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $adminCount = User::count();
        $years = Year::orderBy("updated_at", "DESC")->get();
        $externalCount = ExternalIncome::whereHas('year', function ($query) {$query->where('id', '=', Year::where('year_current', 'selected')->value('id'));})
                                ->orderBy("updated_at", "DESC")
                                ->sum('income_price');
        $totalCredit = Payment::where('type','1')
                                ->whereHas('year', function ($query) {$query->where('id', '=', Year::where('year_current', 'selected')->value('id'));})
                                ->orderBy("updated_at", "DESC")
                                ->where('status','=','Paid')
                                ->sum('price');
        $totalAttribute = Payment::where('type','2')
                                ->whereHas('year', function ($query) {$query->where('id', '=', Year::where('year_current', 'selected')->value('id'));})
                                ->orderBy("updated_at", "DESC")
                                ->where('status','=','Paid')
                                ->sum('price');
        $userId = Auth::user()->id;
        $totalPaid =  Payment::where('user_id', '=', $userId)->sum('price');
        $credit = Payment::where('status','!=','Unpaid')
                    ->whereHas('year', function ($query) {$query->where('id', '=', Year::where('year_current', 'selected')->value('id'));})
                    ->orderBy("updated_at", "DESC")
                    ->limit(5)->get();
        $sumDebit = Payment::whereHas('year', function ($query) {$query->where('id', '=', Year::where('year_current', 'selected')->value('id'));})->where('status', 'Paid')->sum('price');
        $sumSpending = Spending::whereHas('year', function ($query) {$query->where('id', '=', Year::where('year_current', 'selected')->value('id'));})->where('spending_type',1)->sum('spending_price');
        $sumDebt = Debt::where('is_paid',0)->whereHas('year', function ($query) {$query->where('id', '=', Year::where('year_current', 'selected')->value('id'));})->sum('debt_amount');

        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(3)->get();
        return view('home', compact('sumDebit','sumSpending','sumDebt','adminCount', 'notifications','totalCredit','totalAttribute','totalPaid','externalCount','credit','years'));
    }
}
