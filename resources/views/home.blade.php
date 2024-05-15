@extends('layouts.admin.app')

@section('title_page', 'Dashboard')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    @endpush

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin :students="$classList"></x-sidebarAdmin>

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header d-flex justify-content-between">
                    <div class="title">
                        <h1>{{ __('Dashboard') }}</h1>
                    </div>

                    @can('access-currentYear')
                        <div class="current__year d-flex py-lg-0 pt-3 pb-1">
                            <div class="year__active mr-2">
                                <select class="form-control" name="year_name" disabled>
                                    @foreach ($years as $item)
                                        <option value="{{ $item->year_name }}"
                                            {{ $item->year_status == 'active' ? 'selected' : '' }}>
                                            Tahun Ajaran: {{ $item->year_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endcan

                    @can('access-changeYear')
                        <div class="d-md-flex d-inline">
                            @can('access-downloadRealisasi')
                                <button class="btn btn-success mx-2 my-3 my-md-0" data-toggle="modal" data-target="#exportModal"><i
                                        class="fas fa-file mx-1"></i> Export
                                    Realisasi</button>
                            @endcan
                            <div class="right-content">
                                <div class="d-flex">
                                    <form id="updateYearForm">
                                        @csrf
                                        <div class="current__year d-flex py-lg-0">
                                            <div class="year__active mr-2">
                                                <select class="form-control" name="year_name">
                                                    @foreach ($years as $item)
                                                        <option value="{{ $item->year_name }}"
                                                            {{ $item->year_current == 'selected' ? 'selected' : '' }}>
                                                            Tahun Ajaran: {{ $item->year_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="button-submit">
                                                <button type="button" onclick="updateYear()"
                                                    class="btn btn-primary h-100">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>

                <div class="row">
                    @can('access-recentDebite')
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-primary">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Pemasukan</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <h5>
                                            Rp{{ number_format($sumDebit + $externalCount, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    @can('access-userSum')
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-primary">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total User</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <div id="admin-count">
                                            <h5>{{ $adminCount }}</h5>
                                        </div>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                        <script>
                            function fetchAdminCount() {
                                fetch('/get-admin-count')
                                    .then(response => response.json())
                                    .then(data => {
                                        document.getElementById('admin-count').innerHTML = `<h5>${data.adminCount}</h5>`;
                                    });
                            }
                            setInterval(fetchAdminCount, 300000);
                        </script>
                    @endcan
                    @can('access-externalIncomeSum')
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-success">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Pemasukan Eksternal</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <div id="external-count">
                                            <h5>Rp {{ number_format($externalCount, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    @can('access-incomeSum')
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-warning">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>SPP</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <div id="total-credit">
                                            <h5>Rp {{ number_format($totalCredit, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    @can('access-allAttributeSum')
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-danger">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Daftar Ulang</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <h5>Rp {{ number_format($totalAttribute, 0, ',', '.') }}</h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                    @endcan


                    @can('access-paidSum')
                        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-success">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Pembayaran Lunas</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <h4>Rp {{ number_format($totalPaid, 0, ',', '.') }}</h4>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-danger">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total SPP Belum Lunas</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <h4>Rp {{ number_format($totalUnpaidSPP, 0, ',', '.') }}</h4>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-warning">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Daftar Ulang Belum Lunas</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <h4>Rp {{ number_format($totalUnpaidDU, 0, ',', '.') }}</h4>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>
                <div class="row">
                    @can('access-recentBalance')
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-primary">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Sisa Saldo</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <h5>
                                            Rp{{ number_format($sumDebit - $totalBahan - $sumSpending + $externalCount - $sumDebtPay, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    @can('access-recentBahan')
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-success">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Pengeluaran Sekolah</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <h5>
                                            Rp{{ number_format($totalBahan, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    @can('access-recentHutang')
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-warning">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Hutang</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <h5>
                                            Rp{{ number_format($sumDebt, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    @can('access-recentKredit')
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-danger">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Pengeluaran SPP & Daftar Ulang</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <h5>
                                            Rp{{ number_format($sumSpending, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>

                @can('access-todayTransaction')
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-primary">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Pemasukan Bulan Ini</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <h5>
                                            Rp{{ number_format($sumMonthPrice, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-success">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Pemasukan Harian</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <h5>
                                            Rp{{ number_format($sumTodayPrice, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-warning">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Daftar Ulang Harian</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <h5>
                                            Rp{{ number_format($attributeTodayPrice, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-danger">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>SPP Harian</h4>
                                    </div>
                                    <div class="card-body py-2">
                                        <h5>
                                            Rp{{ number_format($creditTodayPrice, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                <div class="row">
                    @can('access-recentTransaction')
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Transaksi Terbaru</h4>
                                    @if ($credit->isNotEmpty())
                                        <div class="card-header-action">
                                            <a href="{{ url('transaction/recent') }}" class="btn btn-danger">View More <i
                                                    class="fas fa-chevron-right"></i></a>
                                        </div>
                                    @endif

                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive table-invoice">
                                        @if ($credit->isEmpty())
                                            <p class="text-center">Belum ada transaksi pada periode ini</p>
                                        @else
                                            <table class="table table-striped">
                                                <tr>
                                                    <th>ID Transaksi</th>
                                                    <th>Pembayaran</th>
                                                    <th>Nama</th>
                                                    <th>Status</th>
                                                    <th>Tanggal</th>
                                                </tr>
                                                @foreach ($credit as $item)
                                                    <tr>
                                                        <td>{{ $item->invoice_number ?? '-' }}</td>
                                                        <td>{{ $item->payment_type }}</td>
                                                        <td>{{ $item->user->name }}</td>
                                                        <td>
                                                            @if ($item->status == 'Paid')
                                                                <div class="badge badge-success">Lunas</div>
                                                            @elseif($item->status != 'Paid')
                                                                <div class="badge badge-warning">{{ $item->status }}</div>
                                                            @endif

                                                        </td>
                                                        <td>{{ $item->updated_at->format('F d, Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan
                    @can('access-notification')
                        <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Aktivitas Terbaru</h4>
                                    <div class="card-header-action">
                                        <a href="{{ url('/notifications') }}" class="btn btn-primary">View More <i
                                                class="fas fa-chevron-right"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled list-unstyled-border">
                                        @foreach ($notifications as $item)
                                            <li class="media">
                                                <img class="mr-3 rounded-circle" width="50"
                                                    src="assets/img/avatar/avatar-1.png" alt="avatar">
                                                <div class="media-body">
                                                    <span class="text-small">{{ $item->notification_content }}</span>
                                                    <div class="media-title py-1">
                                                        <span
                                                            class="text-small text-muted">{{ Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>

                @can('access-paidList')
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4>{{ __('Tabel Pembayaran Siswa') }}</h4>
                        </div>
                        <div class="card-body d-md-none d-inline">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-tagihan-mobile">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Pembayaran') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($credits as $item)
                                            <tr>
                                                <td>
                                                    <div class="name-text font-weight-bold">
                                                        @if ($item->credit == null)
                                                            {{ $item->attribute->attribute_name }}
                                                        @elseif($item->credit != null)
                                                            {{ $item->credit->credit_name }}
                                                        @endif
                                                    </div>
                                                    <div class="type-text pt-4">
                                                        <span
                                                            class="d-md-none">{{ 'Tipe' . ' ' . ':' . ' ' . $item->type }}</span>
                                                    </div>
                                                    <div class="price-text pt-2">
                                                        <span
                                                            class="d-md-none">{{ 'Nominal' . ' ' . ':' . ' ' . 'Rp' . number_format($item->price, 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="status-text pt-2 d-md-none">
                                                        @if ($item->status == 'Paid')
                                                            <div class="badge badge-success">Lunas</div>
                                                        @else
                                                            <div class="badge badge-danger">Belum Lunas</div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-body d-md-inline d-none">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-tagihan-web">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">{{ __('No') }}</th>
                                            <th>{{ __('Pembayaran') }}</th>
                                            <th>{{ __('Tipe') }}</th>
                                            <th>{{ __('Nominal') }}</th>
                                            <th>{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($credits as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <div class="name-text">
                                                        @if ($item->credit == null)
                                                            {{ $item->attribute->attribute_name }}
                                                        @elseif($item->credit != null)
                                                            {{ $item->credit->credit_name }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $item->type }}</td>
                                                <td>
                                                    Rp{{ number_format($item->price, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    @if ($item->status == 'Paid')
                                                        <div class="badge badge-success">Lunas</div>
                                                    @else
                                                        <div class="badge badge-danger">Belum Lunas</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endcan
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                Development by Muhammad Afifudin</a>
            </div>
        </footer>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="exportModal">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Realisasi Dana</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-action" method="post" action="{{ route('exportRealisasi') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row pt-3 pb-1">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="year_name">Tahun Ajaran</label>
                                    <select class="form-control" name="nama_tahun" id="year_name" required>
                                        <option value=""> -- Pilih Tahun Ajaran -- </option>
                                        @foreach ($years as $item)
                                            <option value="{{ $item->year_name }}">{{ $item->year_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="year_name">Tanggal Mulai</label>
                                    <input type="date" id="start_date" class="form-control" name="start_date"
                                        required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="year_name">Tanggal Selesai</label>
                                    <input type="date" id="finish_date" class="form-control" name="finish_date"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Export Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function updateYear() {
            const form = document.getElementById('updateYearForm');
            const formData = new FormData(form);

            fetch('/current-year', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Terjadi kesalahan');
                    }
                    return response.json();
                })
                .then(data => {
                    Notiflix.Notify.success(data.message, {
                        timeout: 3000
                    });
                    location.reload();
                })
                .catch(error => {
                    Notiflix.Notify.failure('Error: Data tidak ditemukan!');
                });
        }
    </script>

    @push('scripts')
        <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
        <script>
            document.getElementById('form-action').addEventListener('submit', function(event) {
                event.preventDefault();
                var startDate = new Date(document.getElementById('start_date').value);
                var finishDate = new Date(document.getElementById('finish_date').value);

                if (finishDate <= startDate) {
                    Notiflix.Notify.failure('Tanggal selesai harus lebih dari tanggal mulai');
                } else {
                    event.target.submit();
                }
            });
        </script>

        <script>
            $("#table-tagihan-mobile").dataTable();
            $("#table-tagihan-web").dataTable({
                pageLength: 25
            });
        </script>
    @endpush
@endsection
