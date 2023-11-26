@extends('layouts.admin.app')

@section('title_page', 'Credit Payment')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    @endpush

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Pembayaran SPP Siswa') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('Pembayaran') }}</div>
                        <div class="breadcrumb-item active">{{ __('SPP') }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Daftar Tagihan SPP') }}</h2>
                    </div>
                </div>
                <div class="row">
                    @foreach ($credit as $item)
                        @if ($item->status == 'Paid')
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h4>{{ 'Pembayaran Lunas' }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="py-2 mb-3">
                                            <div class="box py-5 bg-success rounded fs-2 text-center text-white">
                                                <h1><i class="bi bi-check2-circle"></i></h1>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-calendar2 mr-2"></i>
                                            <span>Tahun Pelajaran {{ $item->year->year_name }}</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-wallet2 mr-2"></i>
                                            <span>{{ $item->credit->credit_name }}</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-currency-dollar mr-2"></i>
                                            <span>Rp{{ number_format($item->credit->credit_price, 0, ',', '.') }}</span>
                                        </div>
                                        <a href="{{ url('payment/credit/detail/' . $item->id) }}">
                                            <div class="py-2">
                                                <button class="btn btn-outline-success w-100">Bukti Pembayaran</button>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @elseif ($item->status == 'Unpaid')
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card card-warning">
                                    <div class="card-header d-flex justify-content-between">
                                        <h4>{{ 'Pembayaran Belum Lunas' }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="py-2 mb-3">
                                            <div class="box py-5 bg-warning rounded fs-2 text-center text-white">
                                                <h1><i class="bi bi-pause-circle"></i></h1>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-calendar2 mr-2"></i>
                                            <span>Tahun Pelajaran {{ $item->year->year_name }}</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-wallet2 mr-2"></i>
                                            <span>{{ $item->credit->credit_name }}</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-currency-dollar mr-2"></i>
                                            <span>Rp{{ number_format($item->credit->credit_price, 0, ',', '.') }}</span>
                                        </div>
                                        <a href="{{ url('payment/credit/detail/' . $item->id) }}">
                                            <div class="py-2">
                                                <button class="btn btn-warning w-100">Bayar Sekarang</button>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                {{ __('Development by Muhammad Afifudin') }}</a>
            </div>
        </footer>
    </div>
    @push('scripts')
    @endpush
@endsection
