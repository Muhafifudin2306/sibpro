@extends('layouts.admin.app')

@section('title_page')
    List of {{ $class->class_name }}
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
                        <div class="breadcrumb-item">{{ $class->class_name }}</div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Data Kelas') }} {{ $class->class_name }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih dan Edit Pembayaran Siswa') }}
                        </p>
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
                                        <tr class="text-center">
                                            <th class="text-center">{{ __('No') }}</th>
                                            <th>{{ __('NIS') }}</th>
                                            <th>{{ __('Nama Siswa') }}</th>
                                            <th>{{ __('Kategori') }}</th>
                                            <th>{{ __('Pembayaran') }}</th>
                                            <th>{{ __('Total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($students as $item)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $no++ }}
                                                </td>
                                                <td>
                                                    {{ $item->nis }}
                                                </td>
                                                <td>
                                                    {{ $item->name }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->categories->category_name }}
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex flex-wrap">
                                                        @php
                                                            $totalCreditPrice = 0;
                                                        @endphp
                                                        @foreach ($item->credits as $credit)
                                                            <div class="mb-2 mx-1">
                                                                @if ($credit->status == 'Unpaid')
                                                                    <a
                                                                        href="{{ url('income/credit/payment/' . $credit->user_credit_id) }}">
                                                                        <button class="btn btn-danger" type="submit"
                                                                            id="pay-button">{{ $credit->credit_name }}</button>
                                                                    </a>
                                                                @elseif($credit->status == 'Paid')
                                                                    <button class="btn btn-success" data-toggle="modal"
                                                                        data-target="#creditModal{{ $credit->id }}">{{ $credit->credit_name }}
                                                                        (Lunas )</button>
                                                                @endif
                                                            </div>
                                                            @php
                                                                $totalCreditPrice += $credit->credit_price;
                                                            @endphp
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td>
                                                    Rp{{ number_format($totalCreditPrice, 0, ',', '.') }}
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
