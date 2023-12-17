@extends('layouts.admin.app')

@section('title_page', 'All Transaction')

@section('content')
    @push('styles')
        @can('access-classList')
            <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
        @endcan
    @endpush

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Data Transaksi SPP') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('Pemasukan') }}</div>
                        <div class="breadcrumb-item active">{{ __('Data Transaksi SPP') }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Data Transaksi SPP') }}</h2>
                    </div>
                    <div class="action-content">
                        <button class="btn btn-warning">{{ __('Print Data') }}</button>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Tabel Data Transaksi') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-tagihan-vendor">
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{ __('No') }}</th>
                                            <th>{{ __('ID Transaksi') }}</th>
                                            <th>{{ __('Pembayaran') }}</th>
                                            <th>{{ __('Tipe Pembayaran') }}</th>
                                            <th>{{ __('Nama Siswa') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Tanggal') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($credit as $item)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $no++ }}
                                                </td>
                                                <td>{{ $item->invoice_number }}</td>
                                                @if ($item->credit == null)
                                                    <td>{{ $item->attribute->attribute_name }}</td>
                                                @elseif($item->credit != null)
                                                    <td>{{ $item->credit->credit_name }}</td>
                                                @endif
                                                @if ($item->type == 1)
                                                    <td>SPP</td>
                                                @elseif($item->type == 2)
                                                    <td>Daftar Ulang</td>
                                                @endif
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
                {{ __('Development by Muhammad Afifudin') }}</a>
            </div>
        </footer>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
    @endpush
@endsection
