<style>
    body {
        font-family: 'Times New Roman', Times, serif !important;
    }
</style>


<table>
    <thead>
        <tr>
            <td align="center" style="font-size: 12px;font-family: 'Times New Roman', Times, serif" colspan="8">
                <b>REALISASI PENGGUNAAN DANA</b>
            </td>
        </tr>
        <tr>
            <td align="center" style="font-size: 12px;font-family: 'Times New Roman', Times, serif" colspan="8">
                <b>SMK BP DARUL ULUM REJOSARI</b>
            </td>
        </tr>
        <tr>
            <td align="center" style="font-size: 12px;font-family: 'Times New Roman', Times, serif" colspan="8">
                <b>TAHUN PELAJARAN {{ $activeYearId }} </b>
            </td>
        </tr>
        <tr colspan="8">
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 12px;font-family: 'Times New Roman', Times, serif">PERIODE</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif">:
                {{ date('d-M-y', strtotime($start_date)) . ' s/d ' . date('d-M-y', strtotime($finish_date)) }}
            </td>
        </tr>
        <tr>
            <td colspan="4"align="center"
                style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle">
                <b>PEMASUKAN</b>
            </td>
            <td colspan="4" align="center"
                style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle">
                <b>PENGELUARAN</b>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td align="center"
                style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle" width="5">
                <b>NO</b>
            </td>
            <td align="center"
                style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle" width="15">
                <b>TGL</b>
            </td>
            <td align="center"
                style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle" width="40">
                <b>URAIAN</b>
            </td>
            <td align="center"
                style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle" width="20">
                <b>JUMLAH</b>
            </td>
            <td align="center"
                style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle" width="5">
                <b>NO</b>
            </td>
            <td align="center"
                style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle" width="15">
                <b>TGL</b>
            </td>
            <td align="center"
                style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle" width="45">
                <b>URAIAN</b>
            </td>
            <td align="center"
                style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle" width="20">
                <b>JUMLAH</b>
            </td>
        </tr>
        {{-- <tr>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center"></td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"></td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                <b>Saldo Anggaran</b>
            </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
            </td>

            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
        </tr> --}}
        {{-- <tr>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center">1</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ date('d-M-Y', strtotime($finish_date)) }}</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">Saldo
                Periode
                Sebelumnya</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ $sumDebit + $externalCount - $sumDebtPay }}
            </td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
        </tr> --}}
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>

            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center"></td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"></td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                <b>Pengeluaran Operasional</b>
            </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center">1</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ date('d-M-Y', strtotime($finish_date)) }}</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">Anggaran
                Pemeliharaan dan perbaikan berkala</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ 'Rp' . number_format($maintenanceSpending, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>

            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center">2</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ date('d-M-Y', strtotime($finish_date)) }}</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">Anggaran
                kesiswaan </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ 'Rp' . number_format($kesiswaanSpending, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>

            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center">3</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ date('d-M-Y', strtotime($finish_date)) }}</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">Anggaran
                Daya dan Jasa </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ 'Rp' . number_format($jasaSpending, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>

            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center">4</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ date('d-M-Y', strtotime($finish_date)) }}</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">Anggaran
                Belanja bahan habis pakai </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ 'Rp' . number_format($bahanSpending, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>

            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center">5</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ date('d-M-Y', strtotime($finish_date)) }}</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">Anggaran
                belanja alat tulis </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ 'Rp' . number_format($atkSpending, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>

            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center">6</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ date('d-M-Y', strtotime($finish_date)) }}</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">Anggaran
                konsumsi </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ 'Rp' . number_format($konsumSpending, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>

            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center">7</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ date('d-M-Y', strtotime($finish_date)) }}</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">Anggaran
                pengembangan Guru dan Tendik </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ 'Rp' . number_format($tendikSpending, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>

            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center">8</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ date('d-M-Y', strtotime($finish_date)) }}</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">Anggaran
                Transportasi dan perjalanan dinas </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ 'Rp' . number_format($transportSpending, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>

            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center">9</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ date('d-M-Y', strtotime($finish_date)) }}</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">Lain-lain
            </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ 'Rp' . number_format($lainSpending, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>

            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center">10</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ date('d-M-Y', strtotime($finish_date)) }}</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">Keperluan
                SPP dan Daftar Ulang
            </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ 'Rp' . number_format($tagihanSpending, 2, ',', '.') }}
            </td>
        </tr>
        @if ($externalIncome != null)
            <tr>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                    align="center"></td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"></td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                    <b>Pemasukan Eksternal</b>
                </td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                </td>

                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
            </tr>
        @endif

        @php
            $no = 1;
        @endphp
        @foreach ($externalIncome as $item)
            <tr>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                    align="center">{{ $no++ }}</td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                    {{ date('d-M-Y', strtotime($item->income_period)) }}
                </td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                    {{ $item->income_desc }}
                </td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                    {{ 'Rp' . number_format($item->income_price, 2, ',', '.') }}
                </td>

                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
            </tr>
        @endforeach

        @if ($externalSpending != null)
            <tr>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>

                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                    align="center"></td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"></td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                    <b>Pengeluaran Non-Operasional</b>
                </td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                </td>
            </tr>
        @endif
        @php
            $number = 11;
        @endphp
        @foreach ($externalSpending as $item)
            <tr>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                    align="center">{{ $number++ }}</td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                    {{ date('d-M-Y', strtotime($item->spending_date)) }}
                </td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                    {{ $item->spending_desc }}
                </td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                    {{ 'Rp' . number_format($item->spending_price, 2, ',', '.') }}
                </td>
            </tr>
        @endforeach

        <tr>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center"></td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"></td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                <b>Pemasukan SPP dan Daftar Ulang</b>
            </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
            </td>

            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
        </tr>

        <tr>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center">{{ $no }}</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ date('d-M-Y', strtotime($finish_date)) }}
            </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                Pembayaran SPP
            </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ 'Rp' . number_format($paymentSPP, 2, ',', '.') }}
            </td>

            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
        </tr>
        <tr>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                align="center">{{ $no + 1 }}</td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ date('d-M-Y', strtotime($finish_date)) }}
            </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                Pembayaran Daftar Ulang
            </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                {{ 'Rp' . number_format($paymentDU, 2, ',', '.') }}
            </td>

            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
        </tr>

        @if ($debt != '')
            <tr>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                    align="center"></td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"></td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                    <b>Beban Hutang</b>
                </td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                </td>
            </tr>
        @endif

        @foreach ($debt as $item)
            <tr>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                    align="center">{{ $number++ }}</td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                    {{ date('d-M-Y', strtotime($item->due_date)) }}
                </td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                    {{ $item->description }}
                </td>
                <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black">
                    {{ 'Rp' . number_format($item->dedbt_amount, 2, ',', '.') }}
                </td>
            </tr>
        @endforeach

        <tr>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
            <td style="border: 1px solid black"></td>
        </tr>
        <tr>
            <td align="center"
                style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle" width="5" colspan="3">
                <b>JUMLAH</b>
            </td>
            @php
                $totalPemasukan = $paymentSPP + $paymentDU + $externalIncomeSum;
            @endphp
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle" width="20">
                <b>{{ 'Rp' . number_format($totalPemasukan, 2, ',', '.') }}</b>
            </td>
            <td align="center"
                style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle" width="5" colspan="3">
                <b>JUMLAH</b>
            </td>
            @php
                $totalPengeluaran =
                    $maintenanceSpending +
                    $kesiswaanSpending +
                    $jasaSpending +
                    $bahanSpending +
                    $atkSpending +
                    $konsumSpending +
                    $tendikSpending +
                    $transportSpending +
                    $lainSpending +
                    $tagihanSpending +
                    $externalSpendingSum +
                    $debtSum;
            @endphp
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle" width="20">
                <b>{{ 'Rp' . number_format($totalPengeluaran, 2, ',', '.') }}</b>
            </td>
        </tr>
        <tr>
            <td align="center"
                style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle" width="5" colspan="7">
                <b>SALDO AKHIR</b>
            </td>
            <td style="font-size: 12px;font-family: 'Times New Roman', Times, serif; border: 1px solid black"
                height="30" valign="middle" width="20">
                <b>{{ 'Rp' . number_format($totalPemasukan - $totalPengeluaran, 2, ',', '.') }}</b>
            </td>
        </tr>
    </tbody>
</table>
