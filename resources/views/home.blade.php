@extends('layouts.admin.app')

@section('title_page', 'Dashboard')

@section('content')
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header d-flex justify-content-lg-between">
                    <div class="title">
                        <h1>{{ __('Dashboard') }}</h1>
                    </div>
                    <form id="updateYearForm">
                        @csrf
                        <div class="current__year d-flex py-lg-0 pt-3 pb-1">
                            <div class="semester__active mr-2">
                                <select class="form-control" name="year_semester">
                                    @foreach ($years as $item)
                                        <option value="{{ $item->year_semester }}"
                                            {{ $item->year_current == 'selected' ? 'selected' : '' }}>
                                            Semester: {{ $item->year_semester }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
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
                                <button type="button" onclick="updateYear()" class="btn btn-primary h-100">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    @can('access-userSum')
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-primary">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total User</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <h5>{{ $adminCount }}</h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="bg-success">
                                <div class="py-1"></div>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Dana Eksternal</h4>
                                </div>
                                <div class="card-body py-1">
                                    <h5>Rp {{ number_format($externalCount, 0, ',', '.') }}</h5>
                                </div>
                                <div class="py-2"></div>
                            </div>
                        </div>
                    </div>

                    @can('access-incomeSum')
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-warning">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>SPP</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <h5>Rp {{ number_format($totalCredit, 0, ',', '.') }}</h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="bg-danger">
                                <div class="py-1"></div>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Daftar Ulang</h4>
                                </div>
                                <div class="card-body py-1">
                                    <h5>Rp {{ number_format($totalAttribute, 0, ',', '.') }}</h5>
                                </div>
                                <div class="py-2"></div>
                            </div>
                        </div>
                    </div>

                    @can('access-paidSum')
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-danger">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Pembayaran</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <h4>Rp {{ number_format($totalPaid, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="bg-primary">
                                <div class="py-1"></div>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Sisa Saldo</h4>
                                </div>
                                <div class="card-body py-1">
                                    <h5>
                                        Rp{{ number_format($sumDebit - $sumSpending, 0, ',', '.') }}
                                    </h5>
                                </div>
                                <div class="py-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="bg-success">
                                <div class="py-1"></div>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Debit</h4>
                                </div>
                                <div class="card-body py-1">
                                    <h5>
                                        Rp{{ number_format($sumDebit, 0, ',', '.') }}
                                    </h5>
                                </div>
                                <div class="py-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="bg-warning">
                                <div class="py-1"></div>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Hutang</h4>
                                </div>
                                <div class="card-body py-1">
                                    <h5>
                                        Rp{{ number_format($sumDebt, 0, ',', '.') }}
                                    </h5>
                                </div>
                                <div class="py-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="bg-danger">
                                <div class="py-1"></div>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Kredit</h4>
                                </div>
                                <div class="card-body py-1">
                                    <h5>
                                        Rp{{ number_format($sumSpending, 0, ',', '.') }}
                                    </h5>
                                </div>
                                <div class="py-2"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>Transaksi Terbaru</h4>
                                @if ($credit->isNotEmpty())
                                    <div class="card-header-action">
                                        <a href="{{ url('/income/payment/all') }}" class="btn btn-danger">View More <i
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
                                                    <td>{{ $item->invoice_number }}</td>
                                                    @if ($item->credit == null)
                                                        <td>{{ $item->attribute->attribute_name }}</td>
                                                    @elseif($item->credit != null)
                                                        <td>{{ $item->credit->credit_name }}</td>
                                                    @endif
                                                    <td>{{ $item->user->name }}</td>
                                                    <td>
                                                        @if ($item->status == 'Pending')
                                                            <div class="badge badge-warning">{{ $item->status }}</div>
                                                        @elseif($item->status == 'Paid')
                                                            <div class="badge badge-success">{{ $item->status }}</div>
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
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                Development by Muhammad Afifudin</a>
            </div>
        </footer>
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


@endsection
