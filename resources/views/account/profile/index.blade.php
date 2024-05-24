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
                        <div class="col-12 col-md-5">
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
                        <div class="col-12 col-md-7">
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
                                                <label>{{ __('NISN') }}</label>
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
                                            <div class="form-group col-md-6 col-12">
                                                <label>{{ __('Email') }}</label>
                                                <input type="email" class="form-control"
                                                    value="{{ $user->user_email ? $user->user_email : 'Tidak ada data email' }}"
                                                    disabled>
                                            </div>
                                            <div class="form-group col-md-6 col-12">
                                                <label>{{ __('No Telp Siswa') }}</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $user->number ? $user->number : 'Tidak ada data telpon siswa' }}"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-5"></div>
                        <div class="col-12 col-md-7">
                            <div class="card">
                                <form class="update-form"
                                    data-action="{{ url('/account/users/update-password/' . $student->uuid) }}"
                                    method="POST">
                                    @csrf
                                    <div class="card-header">
                                        <h4>{{ __('Ubah Informasi Akun Saya') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="emailUser" class="d-block">{{ __('Email Baru') }}</label>
                                                <input id="emailUser" type="email" class="form-control lock"
                                                    name="emailUser"
                                                    placeholder="{{ $user->user_email ? $user->user_email : 'Input email anda' }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="noTelpUser" class="d-block">{{ __('No Telpon Baru') }}</label>
                                                <input id="noTelpUser" type="tel" class="form-control lock"
                                                    name="noTelpUser"
                                                    placeholder="{{ $user->number ? $user->number : 'Input no telp anda' }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="password" class="d-block">{{ __('Password Baru') }}</label>
                                                <input id="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror pwstrength lock"
                                                    name="password" placeholder="*********" data-indicator="pwindicator"
                                                    autocomplete="new-password">
                                                <div id="pwindicator" class="pwindicator">
                                                    <div class="bar"></div>
                                                    <div class="label"></div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="password-confirm"
                                                    class="d-block">{{ __('Konfirmasi Password') }}</label>
                                                <input id="password-confirm" type="password" class="form-control lock"
                                                    name="password_confirmation" placeholder="*********"
                                                    autocomplete="new-password">
                                            </div>
                                        </div>
                                    </div>


                            </div>
                            <div class="button-submit">
                                <button class="btn btn-primary"> Simpan Data </button>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const updateForms = document.querySelectorAll('.update-form');
            const phonePattern = /^[0-9]{10,15}$/;
            const PASSWORD_MISMATCH_MESSAGE = "Password baru dan konfirmasi password baru tidak sama";
            const CONFIRM_PASSWORD_MESSAGE = "Isi konfirmasi password";
            const INVALID_PHONE_MESSAGE = "Please enter a valid phone number.";
            const SUCCESS_MESSAGE = "Data berhasil diperbarui!";
            const CSRF_TOKEN = '{{ csrf_token() }}';

            updateForms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const password = form.querySelector('#password');
                    const passwordConfirm = form.querySelector('#password-confirm');
                    const noTelpUser = form.querySelector('#noTelpUser');

                    if (!validateForm(noTelpUser, password, passwordConfirm)) {
                        return;
                    }

                    submitForm(form);
                });
            });

            function validateForm(noTelpUser, password, passwordConfirm) {
                if (noTelpUser.value && !phonePattern.test(noTelpUser.value)) {
                    Notiflix.Notify.warning(INVALID_PHONE_MESSAGE, { timeout: 3000 });
                    noTelpUser.focus();
                    return false;
                }

                if (password.value && !passwordConfirm.value) {
                    Notiflix.Notify.warning(CONFIRM_PASSWORD_MESSAGE, { timeout: 3000 });
                    passwordConfirm.focus();
                    return false;
                } else if (password.value !== passwordConfirm.value) {
                    Notiflix.Notify.warning(PASSWORD_MISMATCH_MESSAGE, { timeout: 3000 });
                    passwordConfirm.focus();
                    return false;
                }

                return true;
            }

            function submitForm(form) {
                const formData = new FormData(form);
                const actionUrl = form.getAttribute('data-action');

                fetch(actionUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    Notiflix.Notify.success(SUCCESS_MESSAGE, { timeout: 3000 });
                    location.reload();
                })
                .catch(error => {
                    Notiflix.Notify.failure('Error: ' + error.message);
                });
            }
        });
    </script>
@endpush

