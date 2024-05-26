@extends('layouts.admin.app')

@section('title_page', 'Pembayaran Berhasil')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
        <style>
            .transaction-item {
                margin-bottom: 20px;
            }

            .transaction-header h5 {
                margin-bottom: 10px;
            }

            .transaction-details p {
                margin: 5px 0;
            }

            .detail-link {
                color: blue;
                text-decoration: underline;
            }

            @media (min-width: 992px) {
                .d-lg-none {
                    display: none !important;
                }
            }

            @media (max-width: 991px) {
                .d-lg-block {
                    display: none !important;
                }

            }
        </style>
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
                    @can('access-currentYear')
                        <div class="current__year d-md-flex d-block py-lg-0 pt-3 pb-1">
                            <div class="year__active mr-2 mb-3">
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
                        <div class="table-responsive d-none d-lg-block">
                            <table class="table table-striped" id="table-spp">
                                <thead>
                                    <tr>
                                        <th>{{ __('No') }}</th>
                                        <th>{{ __('No. Kwitansi') }}</th>
                                        <th>{{ __('Pembayaran') }}</th>
                                        <th>{{ __('Tipe') }}</th>
                                        <th>{{ __('Nominal') }}</th>
                                        <th>{{ __('Verifikator') }}</th>
                                        <th>{{ __('Tahun') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Tanggal') }}</th>
                                        <th>{{ __('Aksi') }}</th>
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
                                            <td>{{ $item->payment_type }}</td>
                                            <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td>{{ $item->petugas->name }}</td>
                                            <td>{{ $item->year->year_name }}</td>
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
                                            <td><a href="{{ url('/payment-done/detail/' . $item->id) }}"><i
                                                        class="fas fa-file text-primary" title="Detail"></i></a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-lg-none">
                            @foreach ($credit as $item)
                                <div class="transaction-item">
                                    <div class="transaction-header">
                                        <h5>
                                            @if ($item->credit == null)
                                                {{ $item->attribute->attribute_name }}
                                            @elseif($item->credit != null)
                                                {{ $item->credit->credit_name }}
                                            @endif - {{ $item->year->year_name }}
                                        </h5>
                                    </div>
                                    <div class="transaction-details">
                                        <p>No. Kwitansi: {{ $item->invoice_number }}</p>
                                        <p>Status:
                                            @if ($item->status == 'Paid')
                                                <span class="badge badge-success">{{ __('Lunas') }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ $item->status }}</span>
                                            @endif
                                        </p>
                                        <p>Nominal Pembayaran: Rp{{ number_format($item->price, 0, ',', '.') }} via
                                            {{ $item->payment_type }}</p>
                                        <p>Tanggal Pembayaran: {{ $item->updated_at->format('h:i F d, Y') }}</p>
                                        <br>
                                        <p><a href="{{ url('/payment-done/detail/' . $item->id) }}"
                                                class="btn btn-primary">{{ __('Lihat Detail Pembayaran') }}</a></p>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="main-footer">
            {{ __('Development by Muhammad Afifudin') }}</a>
        </div>
    </div>


    @push('scripts')
        <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
    @endpush
@endsection
