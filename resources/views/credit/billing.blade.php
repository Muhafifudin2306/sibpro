@extends('layouts.admin.app')

@section('title_page')
    Billing Student
@endsection

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    @endpush

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Pembayaran SPP') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item active">{{ __('Pemasukan') }}</div>
                        <div class="breadcrumb-item">{{ __('SPP') }}</div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Data Pembayaran') }} </h2>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Tabel Siswa') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-tagihan-vendor">
                                    <thead>
                                        <tr>
                                            <th>{{ __('No') }}</th>
                                            <th>{{ __('Kode Pembayaran') }}</th>
                                            <th>{{ __('Nama Pembayaran') }}</th>
                                            <th>{{ __('Tagihan') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Aksi') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($student as $item)
                                            <tr>
                                                <td>
                                                    {{ $no++ }}
                                                </td>
                                                <td>
                                                    {{ $item->invoice_number }}
                                                </td>
                                                <td>
                                                    {{ $item->credit->credit_name }}
                                                </td>
                                                <td>
                                                    Rp{{ number_format($item->credit->credit_price, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    @if ($item->status == 'Unpaid')
                                                        <span
                                                            class="py-1 px-3 border border-danger rounded text-danger fw-bold"
                                                            type="submit" id="pay-button">Belum Bayar</span>
                                                    @elseif($item->status == 'Paid')
                                                        <span
                                                            class="py-1 px-3 border border-danger rounded text-success fw-bold"
                                                            type="submit" id="pay-button">Lunas</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('income/credit/payment/' . $item->uuid) }}">
                                                        <button class="btn btn-primary">Bayar Sekarang</button>
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                {{ __('Development by Muhammad Afifudin') }}
            </div>
        </footer>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
@endpush
