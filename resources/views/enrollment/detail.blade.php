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
                    <h1>{{ __('Pembayaran Daftar Ulang') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item active">{{ __('Pemasukan') }}</div>
                        <div class="breadcrumb-item active">{{ __('Daftar Ulang') }}</div>
                        <div class="breadcrumb-item">{{ $class->class_name }}</div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Data Kelas') }} {{ $class->class_name }}</h2>
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
                                            <th>{{ __('Kategori') }}</th>
                                            <th>{{ __('Terbayar') }}</th>
                                            <th>{{ __('Tagihan') }}</th>
                                            <th>{{ __('Operasi') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($students as $item)
                                            <tr>
                                                <td>
                                                    {{ $no++ }}
                                                </td>
                                                <td>
                                                    {{ $item->nis }}
                                                </td>
                                                <td>
                                                    {{ $item->name }}
                                                </td>
                                                <td>
                                                    {{ $item->categories->category_name }}
                                                </td>
                                                @php
                                                    $totalattributePrice = 0;
                                                    $totalBilling = 0;
                                                @endphp
                                                <td>
                                                    @foreach ($item->enrollments as $billing)
                                                        @php
                                                            $totalBilling += $billing->attribute_price;
                                                        @endphp
                                                    @endforeach
                                                    Rp{{ number_format($totalBilling, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    @foreach ($item->attributes as $attribute)
                                                        @php
                                                            $totalattributePrice += $attribute->attribute_price;
                                                        @endphp
                                                    @endforeach
                                                    Rp{{ number_format($totalattributePrice, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    <a href="#">
                                                        <button class="btn btn-primary" type="submit"
                                                            id="pay-button">Bayar</button>
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
