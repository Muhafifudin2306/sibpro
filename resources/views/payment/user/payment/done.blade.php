@extends('layouts.admin.app')

@section('title_page', 'Pembayaran Berhasil')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    @endpush

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin :students="$students"></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header d-flex justify-content-lg-between">
                    <div class="title">
                        <h1>{{ __('Pembayaran Berhasil') }}</h1>
                    </div>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item active">{{ __('Pembayaran Berhasil') }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Pembayaran Berhasil') }}</h2>
                        <p class="section-lead">
                            {{ __('Lihat kumpulan transaksi dan unduh kwitansi pembayaran') }}
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Tabel Data Transaksi') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-spp">
                                <thead>
                                    <tr>
                                        <th>{{ __('No') }}</th>
                                        <th>{{ __('ID Transaksi') }}</th>
                                        <th>{{ __('Pembayaran') }}</th>
                                        <th>{{ __('Tipe') }}</th>
                                        <th>{{ __('Nama Siswa') }}</th>
                                        <th>{{ __('Nominal Pembayaran') }}</th>
                                        <th>{{ __('Verifikator') }}</th>
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
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $item->invoice_number }}</td>
                                            @if ($item->credit == null)
                                                <td>{{ $item->attribute->attribute_name }}</td>
                                            @elseif($item->credit != null)
                                                <td>{{ $item->credit->credit_name }}</td>
                                            @endif
                                            <td>{{ $item->type }}</td>
                                            <td>{{ $item->user->name }}</td>
                                            <td>
                                                Rp{{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td>{{ $item->petugas->name }}</td>
                                            @if ($item->status == 'Paid')
                                                <td>
                                                    <div class="badge badge-success">{{ __('Lunas') }}</div>
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
