<?php

namespace App\Http\Controllers;

use App\Exports\RealisasiPemasukanPengeluaranExport;
use App\Models\Bahan;
use App\Models\Debt;
use App\Models\ExternalIncome;
use App\Models\ExternalSpending;
use App\Models\Spending;
use App\Models\User;
use App\Models\Payment;
use App\Models\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Role;
use App\Models\StudentClass;
use App\Models\Year;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        $totalCredits = Payment::where('type', 'SPP')
                                ->where('year_id', $activeYearId)
                                ->where('status', 'Paid')
                                ->get();

        $totalCredit = $totalCredits->reduce(function ($carry, $transaction) {
            return $carry + max($transaction->price - 50000, 0);
        }, 0);

        $totalAttribute = Payment::where('type', 'Daftar Ulang')
                                    ->where('year_id', $activeYearId)
                                    ->where('status', 'Paid')
                                    ->sum('price');

        $totalBelanjaSpending = Bahan::where('year_id', $activeYearId)
                                    ->sum('spending_price');

        $totalExternalSpending = ExternalSpending::where('year_id', $activeYearId)
                                    ->sum('spending_price');

        $totalBahan = $totalBelanjaSpending + $totalExternalSpending;

        // Menghitung total yang dibayarkan oleh pengguna saat ini
        $activeYear = Year::where('year_status', 'active')->value('id');

        $userId = Auth::user()->id;
        $totalPaid =  Payment::where('user_id', $userId)->where('year_id', $activeYear)->where('status', 'Paid')->sum('price');
        $totalUnpaidSPPs =  Payment::where('user_id', $userId)->where('year_id', $activeYear)->where('type', 'SPP')->where('status', '!=','Paid')->get();

        $totalUnpaidSPP = $totalUnpaidSPPs->reduce(function ($carry, $transaction) {
            return $carry + max($transaction->price - 50000, 0);
        }, 0);

        $totalUnpaidDU =  Payment::where('user_id', $userId)->where('year_id', $activeYear)->where('type', 'Daftar Ulang')->where('status', '!=','Paid')->sum('price');
        // Mengambil 5 pembayaran terbaru
        $credit = Payment::where('status','!=','Unpaid')
                            ->whereHas('year', function ($query) {
                                $query->where('id', '=', Year::where('year_current', 'selected')->value('id'));
                            })
                            ->groupBy('invoice_number','user_id', 'status', 'petugas_id', 'updated_at', 'payment_type')
                            ->select('invoice_number','user_id', 'status', 'petugas_id', 'updated_at', 'payment_type', DB::raw('SUM(price) as total_price'))
                            ->orderBy("updated_at", "DESC")
                            ->limit(5)
                            ->get();

        // Menghitung total debit, pengeluaran, dan utang
        $sumDebit = Payment::where('year_id', $activeYearId)
                            ->where('status', 'Paid')
                            ->sum('price');

        $sumSpending = Spending::where('year_id', $activeYearId)
                                ->sum('spending_price');

        $sumDebt = Debt::where('is_paid', 0)
                        ->where('year_id', $activeYearId)
                        ->sum('debt_amount');

        $sumDebtPay = Debt::where('is_paid', 0)
                        ->where('year_id', $activeYearId)
                        ->sum('debt_amount');

        // Mengambil notifikasi terbaru
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")
                                        ->limit(3)
                                        ->get();

        $credits = Payment::orderBy("updated_at", "DESC")
                                        ->where('user_id', Auth::user()->id)
                                        ->where('year_id', $activeYear)
                                        ->get();

        $currentDate = Carbon::now()->toDateString();

        $sumTodayPrice = Payment::where('status', 'Paid')
            ->whereHas('year', function ($query) {
                $query->where('id', '=', Year::where('year_current', 'selected')->value('id'));
            })
            ->whereDate('updated_at', $currentDate)
            ->sum('price');
        $creditTodayPrices = Payment::where('status', 'Paid')
            ->where('type', 'SPP')
            ->whereHas('year', function ($query) {
                $query->where('id', '=', Year::where('year_current', 'selected')->value('id'));
            })
            ->whereDate('updated_at', $currentDate)
            ->get();
        
        $creditTodayPrice = $creditTodayPrices->reduce(function ($carry, $transaction) {
                return $carry + max($transaction->price - 50000, 0);
            }, 0);

        $attributeTodayPrice = Payment::where('status', 'Paid')
            ->where('type', 'Daftar Ulang')
            ->whereHas('year', function ($query) {
                $query->where('id', '=', Year::where('year_current', 'selected')->value('id'));
            })
            ->whereDate('updated_at', $currentDate)
            ->sum('price');

        $currentMonth = Carbon::now()->month;

        $sumMonthPrice = Payment::where('status', 'Paid')
                ->whereHas('year', function ($query) {
                    $query->where('id', '=', Year::where('year_current', 'selected')->value('id'));
                })
                ->whereMonth('updated_at', $currentMonth)
                ->sum('price');

        return view('home', compact('sumMonthPrice','sumTodayPrice','creditTodayPrice','attributeTodayPrice','sumDebtPay','totalBahan', 'totalUnpaidSPP', 'totalUnpaidDU','classList', 'sumDebit', 'sumSpending', 'sumDebt', 'adminCount', 'notifications', 'totalCredit', 'totalAttribute', 'totalPaid', 'externalCount', 'credit', 'years','credits'));
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
    
    public function exportRealisasi(Request $request)
    {
        $namaTahun = str_replace(['/', '\\'], '-', $request->nama_tahun);

        $filterData = [
            'nama_tahun' => $request->nama_tahun,
            'start_date' => $request->start_date,
            'finish_date' => $request->finish_date,
        ];

        $fileName = 'Rekap-Pemasukan-Dana-TP-' . $namaTahun . '.xlsx';

        return Excel::download(new RealisasiPemasukanPengeluaranExport($filterData), $fileName);
    }
}
