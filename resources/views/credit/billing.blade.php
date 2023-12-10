@extends('layouts.admin.app')

@section('title_page')
    Billing of {{ $student->name }}
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
                        <div class="breadcrumb-item active">{{ __('SPP') }}</div>
                        <div class="breadcrumb-item">{{ $student->name }}</div>
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
                                            <th>{{ __('NIS') }}</th>
                                            <th>{{ __('Nama Siswa') }}</th>
                                            <th>{{ __('Tagihan') }}</th>
                                            <th>{{ __('Dibayarkan') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $no++ }}
                                            </td>
                                            <td>
                                                {{ $student->nis }}
                                            </td>
                                            <td>
                                                {{ $student->name }}
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap">
                                                    @foreach ($student->billing as $item)
                                                        <div class="mb-2 mx-1">
                                                            @if ($item->status == 'Unpaid')
                                                                <a
                                                                    href="{{ url('income/credit/payment/' . $item->uuid) }}">
                                                                    <button class="btn btn-danger" type="submit"
                                                                        id="pay-button">{{ $item->credit->credit_name }}</button>
                                                                </a>
                                                            @elseif($item->status == 'Paid')
                                                                <button class="btn btn-success"
                                                                    data-toggle="modal">{{ $item->credit->credit_name }}
                                                                    (Lunas)
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $totalCreditPrice = 0;
                                                @endphp
                                                @foreach ($student->billing as $item)
                                                    @php
                                                        $totalCreditPrice += $item->credit_price;
                                                    @endphp
                                                @endforeach
                                                Rp{{ number_format($totalCreditPrice, 0, ',', '.') }}
                                            </td>
                                        </tr>
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
