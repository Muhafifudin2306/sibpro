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

        $totalBelanjaSpending = Bahan::where('year_id', $activeYearId)
                                    ->sum('spending_price');

        $totalExternalSpending = ExternalSpending::where('year_id', $activeYearId)
                                    ->sum('spending_price');

        $totalBahan = $totalBelanjaSpending + $totalExternalSpending;

        // Menghitung total yang dibayarkan oleh pengguna saat ini
        $activeYear = Year::where('year_status', 'active')->value('id');

        $userId = Auth::user()->id;
        $totalPaid =  Payment::where('user_id', $userId)->where('year_id', $activeYear)->where('status', 'Paid')->sum('price');
        $totalUnpaidSPP =  Payment::where('user_id', $userId)->where('year_id', $activeYear)->where('type', 'SPP')->where('status', '!=','Paid')->sum('price');

        $totalUnpaidDU =  Payment::where('user_id', $userId)->where('year_id', $activeYear)->where('type', 'Daftar Ulang')->where('status', '!=','Paid')->sum('price');
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

        return view('home', compact('sumDebtPay','totalBahan', 'totalUnpaidSPP', 'totalUnpaidDU','classList', 'sumDebit', 'sumSpending', 'sumDebt', 'adminCount', 'notifications', 'totalCredit', 'totalAttribute', 'totalPaid', 'externalCount', 'credit', 'years','credits'));
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
        // Periksa dan atur nama tahun untuk menghindari karakter yang tidak valid
        $namaTahun = str_replace(['/', '\\'], '-', $request->nama_tahun);

        $filterData = [
            'nama_tahun' => $request->nama_tahun,
            'start_date' => $request->start_date,
            'finish_date' => $request->finish_date,
        ];

        // Buat nama file dengan menggunakan nama tahun yang telah disesuaikan
        $fileName = 'Rekap-Pemasukan-Dana-TP-' . $namaTahun . '.xlsx';

        return Excel::download(new RealisasiPemasukanPengeluaranExport($filterData), $fileName);
    }
}
