@extends('layouts.auth.app')
@section('content')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>{{ __('Register SIBPRO') }}</h4>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="name">{{ __('Fullname') }}</label>
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

                                <div class="form-group">
                                    <label for="email">{{ __('Email') }}</label>
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
                                        <label for="password" class="d-block">{{ __('Password') }}</label>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror pwstrength"
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
                                        <label for="password" class="d-block">{{ __('Password Confirmation') }}</label>
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" placeholder="*********" required
                                            autocomplete="new-password">
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
                </div>
            </div>
        </div>
    </section>
    <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('assets/modules/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('assets/js/page/auth-register.js') }}"></script>
@endsection
