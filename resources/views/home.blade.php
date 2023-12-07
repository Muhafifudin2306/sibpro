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
                <div class="section-header">
                    <h1>{{ __('Dashboard') }}</h1>
                </div>
                <div class="row">
                    @can('access-userSum')
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="far fa-user"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total User</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <h4>{{ $adminCount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Role</h4>
                                </div>
                                <div class="card-body py-1">
                                    <h4>{{ $roleCount }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    @can('access-incomeSum')
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-danger">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Pemasukan</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <h4>Rp {{ number_format($totalCredit, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    @can('access-paidSum')
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-danger">
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
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>Transaksi Terbaru</h4>
                                <div class="card-header-action">
                                    <a href="#" class="btn btn-danger">View More <i
                                            class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive table-invoice">
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
                                                <td>{{ $item->credit->credit_name }}</td>
                                                <td class="font-weight-600">{{ $item->user->name }}</td>
                                                @if ($item->status == 'Paid')
                                                    <td>
                                                        <div class="badge badge-success">{{ $item->status }}</div>
                                                    </td>
                                                @else
                                                    <td>
                                                        <div class="badge badge-warning">{{ $item->status }}</div>
                                                    </td>
                                                @endif
                                                <td>{{ $item->updated_at->format('F d, Y') }}</td>
                                            </tr>
                                        @endforeach


                                    </table>
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
            <div class="footer-right">

            </div>
        </footer>
    </div>
@endsection
