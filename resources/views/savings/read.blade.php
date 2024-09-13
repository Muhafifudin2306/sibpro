@extends('layouts.admin.app')

@section('title_page', 'Tabungan Siswa')

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
                        <h1>{{ __('Tabungan Siswa') }}</h1>
                    </div>
                    @can('access-currentYear')
                        <div class="current__year d-flex py-lg-0 pt-3 pb-1">
                            <div class="year__active mr-2">
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
                        <h2 class="section-title">{{ __('Tabungan Siswa') }}</h2>
                        <p class="section-lead">
                            {{ __('Lihat dan monitoring tabungan siswa') }}
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Daftar Tabungan Siswa') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-spp">
                                <thead>
                                    <tr>
                                        <th rowspan="2">{{ __('No') }}</th>
                                        <th rowspan="2">{{ __('NIS') }}</th>
                                        <th rowspan="2">{{ __('Nama') }}</th>
                                        <th class="text-center" colspan="{{ $savings->first()->count() }}">
                                            {{ __('Tabungan') }}</th>
                                    </tr>
                                    <tr>
                                        @foreach ($savings->first() as $item)
                                            <th>
                                                {{ $item->credit->credit_name ?? '-' }}
                                                <br>
                                                (Rp50.0000)
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($savings as $userId => $userSavings)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $userSavings->first()->user->nis }}</td>
                                            <td>{{ $userSavings->first()->user->name }}</td>
                                            @foreach ($userSavings as $saving)
                                                <td>
                                                    @if ($saving->status == 'Unpaid')
                                                        <span
                                                            class="py-1 px-3 border border-danger rounded text-danger fw-bold"
                                                            id="pay-button">Unpaid</span>
                                                    @elseif($saving->status == 'Pending')
                                                        <span
                                                            class="py-1 px-3 border border-warning rounded text-warning fw-bold"
                                                            id="pay-button">Pending</span>
                                                    @elseif($saving->status == 'Paid')
                                                        <span
                                                            class="py-1 px-3 border border-success rounded text-success fw-bold"
                                                            id="pay-button">Lunas</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
