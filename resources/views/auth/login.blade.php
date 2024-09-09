@extends('layouts.auth.app')

@section('title_page', 'Login')

@section('content')
    <section class="loginSection">
        <div class="container mt-5">
            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-md-6 col-lg-5">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <img width="120" class="my-3" src="{{ asset('assets/img/logo/smk-du-logo.png') }}"
                                    alt="">
                            </div>
                            <h6 class="text-center mb-5">Sistem Informasi Bendahara <br>dan Keuangan Siswa</h6>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="email">'NIS/Username <span class="text-danger">*</span> </label>
                                    <input id="email" type="text" placeholder="Masukkan NIS atau Username anda"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required="" autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="d-block">
                                        <label for="password" class="control-label">Password <span
                                                class="text-danger">*</span> </label>
                                        {{-- <div class="float-right">
                                            @if (Route::has('password.request'))
                                                <a class="text-small" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                        </div> --}}
                                    </div>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror pwstrength"
                                        placeholder="******" name="password" required="">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" name="remember" id="remember-me"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="remember-me">{{ __('Ingat Saya') }}</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        <i class="fa fa-sign-in-alt mx-1"></i>
                                        {{ __('LOGIN') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
