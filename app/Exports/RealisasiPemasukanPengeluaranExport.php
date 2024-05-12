<?php

namespace App\Exports;

use App\Models\Bahan;
use App\Models\Debt;
use App\Models\ExternalIncome;
use App\Models\ExternalSpending;
use App\Models\Payment;
use App\Models\Spending;
use App\Models\Year;
use \Maatwebsite\Excel\Concerns\WithTitle;
use \Maatwebsite\Excel\Concerns\FromView;

class RealisasiPemasukanPengeluaranExport implements FromView, WithTitle
{
    protected $filterData;

    public function __construct($filterData)
    {
        $this->filterData = $filterData;
    }

    public function view(): \Illuminate\Contracts\View\View
    {

        $activeYearId = $this->filterData['nama_tahun'];
        $activeYear = Year::where('year_name', $this->filterData['nama_tahun'])->pluck('id');
        $start_date = $this->filterData['start_date'];
        $finish_date = $this->filterData['finish_date'];

        $startDateTimestamp = strtotime($start_date);
        $finishDateTimestamp = strtotime($finish_date);

        $sumDebit = Payment::where('year_id', $activeYear)
            ->where('status', 'Paid')
            ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
            ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
            ->sum('price');

        $externalCount = ExternalIncome::where('year_id', $activeYear)
            ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
            ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
            ->sum('income_price');

        $sumDebtPay = Debt::where('is_paid', 1)
            ->where('year_id', $activeYear)
            ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
            ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
            ->sum('debt_amount');

        $maintenanceSpending = ExternalSpending::where('year_id', $activeYear)
        ->where('spending_type', 1)
        ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
        ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
            ->sum('spending_price');

        $kesiswaanSpending = ExternalSpending::where('year_id', $activeYear)
            ->where('spending_type', 2)
            ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
            ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
                ->sum('spending_price');
        
        $jasaSpending = ExternalSpending::where('year_id', $activeYear)
                ->where('spending_type', 3)
                ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
                ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
                    ->sum('spending_price');

        $bahanSpending = Bahan::where('year_id', $activeYear)
                    ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
                    ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
                    ->sum('spending_price');
        $atkSpending = ExternalSpending::where('year_id', $activeYear)
                    ->where('spending_type', 4)
                    ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
                    ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
                        ->sum('spending_price');

        $konsumSpending = ExternalSpending::where('year_id', $activeYear)
                        ->where('spending_type', 5)
                        ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
                        ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
                            ->sum('spending_price');
        $tendikSpending = ExternalSpending::where('year_id', $activeYear)
                            ->where('spending_type', 6)
                            ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
                            ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
                                ->sum('spending_price');
        $transportSpending = ExternalSpending::where('year_id', $activeYear)
                        ->where('spending_type', 7)
                        ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
                        ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
                            ->sum('spending_price');
        $lainSpending = ExternalSpending::where('year_id', $activeYear)
                        ->where('spending_type', 8)
                        ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
                        ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
                            ->sum('spending_price');
        
        $externalIncome = ExternalIncome::where('year_id', $activeYear)
        ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
        ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
        ->get();

        $externalIncomeSum = ExternalIncome::where('year_id', $activeYear)
        ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
        ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
        ->sum('income_price');

        $externalSpending = ExternalSpending::where('year_id', $activeYear)
        ->where('spending_type', 'Biaya Non-Operasional')
        ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
        ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
        ->get();

        $externalSpendingSum = ExternalSpending::where('year_id', $activeYear)
        ->where('spending_type', 'Biaya Non-Operasional')
        ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
        ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
        ->sum('spending_price');

        $tagihanSpending = Spending::where('year_id', $activeYear)
                                ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
                                ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
                                ->sum('spending_price');

        $paymentSPP = Payment::where('type', 'SPP')
                            ->where('year_id', $activeYear)
                            ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
                            ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
                            ->where('status', 'Paid')
                            ->sum('price');

        $paymentDU = Payment::where('type', 'Daftar Ulang')
                            ->where('year_id', $activeYear)
                            ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
                            ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
                            ->where('status', 'Paid')
                            ->sum('price');

        $debt = Debt::where('is_paid',0)->where('year_id', $activeYear)
        ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
        ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
        ->get();

        $debtSum = Debt::where('is_paid',0)->where('year_id', $activeYear)
        ->where('updated_at', '>=', date('Y-m-d H:i:s', $startDateTimestamp))
        ->where('updated_at', '<=', date('Y-m-d H:i:s', $finishDateTimestamp))
        ->sum('debt_amount');

    
        return view('realisasi.excel', compact('debtSum','externalSpendingSum','debt','tagihanSpending','externalIncomeSum','paymentSPP','paymentDU','externalSpending','externalIncome','lainSpending','transportSpending','tendikSpending','konsumSpending','atkSpending','bahanSpending','jasaSpending','kesiswaanSpending','maintenanceSpending','sumDebtPay','externalCount','sumDebit','activeYearId', 'start_date', 'finish_date'));
    }

    public function title(): string
    {
        return $this->filterData['start_date'] . ' ' . 's/d' . ' ' . $this->filterData['finish_date'];
    }
}
