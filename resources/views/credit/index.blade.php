@extends('layouts.admin.app')

@section('title_page', 'Student List')

@section('content')
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Siswa') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('General Setting') }}</div>
                        <div class="breadcrumb-item active">{{ __('Siswa') }}</div>
                    </div>
                </div>
                <div class="section-body">
                    <h2 class="section-title">{{ __('Data Siswa') }}</h2>
                    <p class="section-lead">
                        {{ __('Pilih kelas untuk mengetahui info lebih lanjut') }}
                    </p>
                    <div class="row">
                        @foreach ($studentClasses as $item)
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="card card-primary">
                                    <div class="card-header-card">
                                        <h6 class="text-center p-4 border-bottom">{{ $item->class_name }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <a href="{{ url('income/credit/detail/' . $item->uuid) }}">
                                            <button class="btn btn-primary w-100">More Detail</button>
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
