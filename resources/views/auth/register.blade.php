@extends('layouts.auth.app')

@section('title_page', 'Register')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    @endpush
    <section class="register-section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>{{ __('Register SIBPRO') }} </h4>
                        </div>
                        <div class="card-body">
                            <form id="registration-form" method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="name">{{ __('Fullname') }} <span class="text-danger">*</span></label>
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" placeholder="My Full Name" required autocomplete="name"
                                        autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="nis">{{ __('NIS') }} <span class="text-danger">*</span></label>
                                        <input id="nis" type="text"
                                            class="form-control @error('nis') is-invalid @enderror" name="nis"
                                            value="{{ old('nis') }}" placeholder="992883" required autocomplete="nis"
                                            autofocus>
                                        @error('nis')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="gender">{{ __('Gender') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="gender">
                                            <option selected disabled>{{ __('-- Pilih Gender --') }}</option>
                                            <option value="Laki-Laki">{{ __('Laki-Laki') }}</option>
                                            <option value="Perempuan">{{ __('Perempuan') }}</option>
                                        </select>
                                        @error('gender')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="nis">{{ __('Kelas') }} <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="class_id">
                                            <option selected disabled>{{ __('-- Pilih Kelas --') }}</option>
                                            @foreach ($classes as $item)
                                                <option value="{{ $item->id }}">{{ $item->class_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('class_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="gender">{{ __('Kategori') }} <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="category_id">
                                            <option selected disabled>{{ __('-- Pilih Kategori --') }}</option>
                                            @foreach ($categories as $item)
                                                <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email">{{ __('Email') }} <span class="text-danger">*</span></label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" placeholder="email@email.com" required
                                        autocomplete="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="password" class="d-block">{{ __('Password') }} <span
                                                class="text-danger">*</span></label>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror pwstrength lock"
                                            name="password" placeholder="*********" data-indicator="pwindicator" required
                                            autocomplete="new-password">
                                        <div id="pwindicator" class="pwindicator">
                                            <div class="bar"></div>
                                            <div class="label"></div>
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="password" class="d-block">{{ __('Password Confirmation') }} <span
                                                class="text-danger">*</span></label>
                                        <input id="password-confirm" type="password" class="form-control lock"
                                            name="password_confirmation" placeholder="*********" required
                                            autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" name="remember"
                                            id="show-password">
                                        <label class="custom-control-label"
                                            for="show-password">{{ __('Show Password') }}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="pb-5 text-center">
                        {{ __('Sudah punya akun?') }} <a href="{{ url('/login') }}">{{ __('Login Sekarang') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @push('scripts')
        <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/modules/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>
        <script src="{{ asset('assets/js/page/auth-register.js') }}"></script>
    @endpush
@endsection
