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
        <x-sidebarAdmin :students="$students"></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header d-flex justify-content-lg-between">
                    <div class="title">
                        <h1>{{ __('Data Tagihan Siswa') }}</h1>
                    </div>
                    @can('access-changeYear')
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
                    @endcan
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Data Tagihan Siswa') }}</h2>
                        <p class="section-lead">
                            {{ __('Lihat kumpulan pembayaran SPP dan Daftar Ulang di semester ini') }}
                        </p>
                    </div>
                    {{-- <div class="action-content">
                        <button class="btn btn-warning">{{ __('Print Data') }}</button>
                    </div> --}}
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Tabel Data Tagihan Siswa') }}</h4>
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
                                            <td class="text-center">
                                                {{ $no++ }}
                                            </td>
                                            <td>{{ $item->invoice_number ?? '-' }}</td>
                                            @if ($item->credit == null)
                                                <td>{{ $item->attribute->attribute_name }}</td>
                                            @elseif($item->credit != null)
                                                <td>{{ $item->credit->credit_name }}</td>
                                            @endif
                                            <td>{{ $item->type }}</td>
                                            <td class="font-weight-600">{{ $item->user->name }}</td>
                                            <td>
                                                Rp{{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td>{{ $item->petugas->name ?? '-' }}</td>
                                            @if ($item->status == 'Paid')
                                                <td>
                                                    <div class="badge badge-success">Lunas</div>
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
    @endpush
@endsection
