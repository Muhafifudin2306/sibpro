<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\Debt;
use App\Models\ExternalIncome;
use App\Models\Spending;
use App\Models\User;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\StudentClass;
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
        $activeYearId = Year::where('year_current', 'selected')->value('id');

        $classList = StudentClass::orderBy("class_name", 'ASC')->get();
        $adminCount = User::count();
        $years = Year::orderBy("updated_at", "DESC")->get();

        // Menghitung total external income
        $externalCount = ExternalIncome::where('year_id', $activeYearId)
                                        ->orderBy("updated_at", "DESC")
                                        ->where('year_id', $activeYearId)
                                        ->sum('income_price');

        // Menghitung total kredit dan atribut yang dibayar
        $totalCredit = Payment::where('type', 'SPP')
                                ->where('year_id', $activeYearId)
                                ->where('status', 'Paid')
                                ->sum('price');

        $totalAttribute = Payment::where('type', 'Daftar Ulang')
                                    ->where('year_id', $activeYearId)
                                    ->where('status', 'Paid')
                                    ->sum('price');

        $totalBahan = Bahan::where('year_id', $activeYearId)
                                    ->sum('spending_price');

        // Menghitung total yang dibayarkan oleh pengguna saat ini
        $userId = Auth::user()->id;
        $totalPaid =  Payment::where('user_id', $userId)->where('year_id', $activeYearId)->where('status', 'Paid')->sum('price');

        // Mengambil 5 pembayaran terbaru
        $credit = Payment::where('status', '!=', 'Unpaid')
                            ->where('year_id', $activeYearId)
                            ->orderBy("updated_at", "DESC")
                            ->limit(5)
                            ->get();

        // Menghitung total debit, pengeluaran, dan utang
        $sumDebit = Payment::where('year_id', $activeYearId)
                            ->where('status', 'Paid')
                            ->sum('price');

        $sumSpending = Spending::where('year_id', $activeYearId)
                                ->where('spending_type', 1)
                                ->sum('spending_price');

        $sumDebt = Debt::where('is_paid', 0)
                        ->where('year_id', $activeYearId)
                        ->sum('debt_amount');

        // Mengambil notifikasi terbaru
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")
                                        ->limit(3)
                                        ->get();

        return view('home', compact('totalBahan', 'classList', 'sumDebit', 'sumSpending', 'sumDebt', 'adminCount', 'notifications', 'totalCredit', 'totalAttribute', 'totalPaid', 'externalCount', 'credit', 'years'));
    }

    public function getAdminCount()
    {
        $adminCount = User::count();

        return response()->json(['adminCount' => $adminCount]);
    }
    
    public function getExternalCount()
    {
        $activeYearId = Year::where('year_current', 'selected')->value('id');
        
        $externalCount = ExternalIncome::where('year_id', $activeYearId)
                                        ->orderBy("updated_at", "DESC")
                                        ->sum('income_price');

        return response()->json(['externalCount' => $externalCount]);
    }

}
