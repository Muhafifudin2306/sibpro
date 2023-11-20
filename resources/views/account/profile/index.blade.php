@extends('layouts.admin.app')

@section('title_page', 'Profile User')

@section('content')
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Profile') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('Account') }}</div>
                        <div class="breadcrumb-item active">{{ __('Profile') }}</div>
                    </div>
                </div>
                <div class="section-body">
                    <h2 class="section-title">Hi, {{ Auth::user()->name }}</h2>
                    <p class="section-lead">
                        {{ __('Information about yourself on this page.') }}
                    </p>

                    <div class="row mt-sm-4">
                        <div class="col-12 col-md-12 col-lg-5">
                            <div class="card profile-widget">
                                <div class="profile-widget-header">
                                    <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}"
                                        class="rounded-circle profile-widget-picture">
                                </div>
                                <div class="profile-widget-description">
                                    <div class="profile-widget-name">{{ Auth::user()->name }} <div
                                            class="text-muted d-inline font-weight-normal">
                                            <div class="slash"></div>
                                            @foreach ($user->roles as $role)
                                                {{ $role->name }}
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @foreach ($user->roles as $role)
                                        {{ $role->name }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                    {{ __('of SMK BP Darul Ulum Rejosari Grobogan') }}</b>.
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-7">
                            <div class="card">
                                <form method="post" class="needs-validation" novalidate="">
                                    <div class="card-header">
                                        <h4>{{ __('User Profile') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-6 col-12">
                                                <label>{{ __('FullName') }}</label>
                                                <input type="text" class="form-control" value="{{ $student->name }}"
                                                    disabled>
                                            </div>
                                            <div class="form-group col-md-6 col-12">
                                                <label>{{ 'NIS' }}</label>
                                                <input type="text" class="form-control" value="{{ $student->nis }}"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-12">
                                                <label>{{ __('Email') }}</label>
                                                <input type="email" class="form-control" value="{{ $user->email }}"
                                                    disabled>
                                            </div>
                                            <div class="form-group col-md-6 col-12">
                                                <label>{{ __('Jenis Kelamin') }}</label>
                                                <input type="email" class="form-control" value="{{ $student->gender }}"
                                                    disabled>
                                            </div>
                                            <div class="form-group col-md-6 col-12">
                                                <label>{{ __('Kelas') }}</label>
                                                <input type="email" class="form-control"
                                                    value="{{ $student->classes->class_name }}" disabled>
                                            </div>
                                            <div class="form-group col-md-6 col-12">
                                                <label>{{ __('Kategori Siswa') }}</label>
                                                <input type="email" class="form-control"
                                                    value="{{ $student->categories->category_name }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
@endsection
