@extends('layouts.admin.app')

@section('title_page', 'Edit User')

@section('content')
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Edit User') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('Account') }}</div>
                        <div class="breadcrumb-item">{{ __('Users') }}</div>
                        <div class="breadcrumb-item active">{{ __('Edit') }}</div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>{{ __('Edit User') }}</h4>
                            <a href="{{ url('/account/users') }}"> {{ __('Close') }} </a>
                        </div>
                        <div class="card-body pb-5">
                            <form class="update-form" data-action="{{ url('account/users/update/' . $user->id) }} }}"
                                method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">{{ __('Fullname') }} <span class="text-danger">*</span></label>
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ $user->name }}" required autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="nis">{{ __('NIS') }} <span
                                                class="text-danger">*</span></label>
                                        <input id="nis" type="text"
                                            class="form-control @error('nis') is-invalid @enderror" name="nis"
                                            value="{{ $user->nis }}" required>
                                        @error('nis')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="gender">{{ __('Gender') }} <span
                                                class="text-danger">*</span></label>
                                        @if ($user->gender == 'Laki-Laki')
                                            <select class="form-control" name="gender">
                                                <option value="Laki-Laki" selected>{{ __('Laki-Laki') }}</option>
                                                <option value="Perempuan">{{ __('Perempuan') }}</option>
                                            </select>
                                        @elseif($user->gender == 'Perempuan')
                                            <select class="form-control" name="gender">
                                                <option value="Laki-Laki">{{ __('Laki-Laki') }}</option>
                                                <option value="Perempuan" selected>{{ __('Perempuan') }}</option>
                                            </select>
                                        @else
                                            <select class="form-control" name="gender">
                                                <option selected disabled>{{ __('--Pilih Gender-') }}</option>
                                                <option value="Laki-Laki">{{ __('Laki-Laki') }}</option>
                                                <option value="Perempuan">{{ __('Perempuan') }}</option>
                                            </select>
                                        @endif
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
                                            <option disabled>{{ __('-- Pilih Kelas --') }}</option>
                                            @foreach ($classes as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $user->class_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->class_name }}
                                                </option>
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
                                        <select class="form-control" name="category_id" disabled>
                                            <option disabled>{{ __('-- Pilih Kategori --') }}</option>
                                            @foreach ($categories as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $user->category_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->category_name }}
                                                </option>
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
                                        value="{{ $user->email }}" placeholder="email@email.com" required
                                        autocomplete="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        {{ __('Edit Data') }}
                                    </button>
                                </div>
                            </form>
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

            updateForms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const formData = new FormData(form);

                    fetch(form.getAttribute('data-action'), {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            Notiflix.Notify.success("Data userebrhasil diperbarui!", {
                                timeout: 3000
                            });

                            location.href = "{{ url('account/users') }}";

                        })
                        .catch(error => {
                            Notiflix.Notify.failure(
                                'Error: Field tidak boleh kosong atau nama sejenis telah digunakan',
                                error);
                        });
                });
            });
        });
    </script>
@endpush
