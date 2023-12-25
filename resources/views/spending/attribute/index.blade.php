@extends('layouts.admin.app')

@section('title_page', 'Attribute Spending List')

@section('content')
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Data Pembelanjaan Atribut Siswa') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('Pengeluaran') }}</div>
                        <div class="breadcrumb-item active">{{ __('Realisasi Atribut Siswa') }}</div>
                    </div>
                </div>
                <div class="section-body">
                    <h2 class="section-title">{{ __('Data Realisasi Atribut Siswa') }}</h2>
                    <p class="section-lead">
                        {{ __('Pilih item atribut untuk mengetahui info lebih lanjut') }}
                    </p>
                    <div class="row py-3">
                        @foreach ($attributes as $item)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card card-secondary">
                                    <div class="card-header-card">
                                        <h6 class="text-center p-4 border-bottom">{{ $item->attribute_name }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <a href="{{ url('spending/attribute/detail/' . $item->slug) }}">
                                            <button class="btn btn-outline-secondary w-100">{{ __('More Detail') }}</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
