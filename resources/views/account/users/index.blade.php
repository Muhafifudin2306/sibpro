@extends('layouts.admin.app')

@section('title_page', 'User List')

@section('content')
    @push('styles')
        @can('access-userList')
            <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
        @endcan
    @endpush

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin :students="$classes"></x-sidebarAdmin>

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Manajemen Pengguna') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('Pengaturan Akun') }}</div>
                        <div class="breadcrumb-item active">{{ __('Manajemen Pengguna') }}</div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pb-3 flex-wrap">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Data Pengguna') }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih dan Tambah Data Pengguna') }}
                        </p>
                    </div>
                    @can('access-userAdd')
                        <div class="action-content d-flex flex-column flex-md-row">
                            <button class="btn btn-success mb-2 mb-md-0 mr-md-2" data-toggle="modal"
                                data-target="#tambahPenggunaBulking"><i class="fas fa-plus mx-1"></i>
                                {{ __(' Tambah Data Dengan Excel') }}</button>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#PenggunaModal"><i
                                    class="fas fa-plus mx-1"></i> {{ __(' Tambah Data') }}</button>
                        </div>
                    @endcan
                </div>

                <!-- Flash Message -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="title-class">
                                <h6> {{ __('Tabel Data Pengguna') }} </h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-Pengguna">
                                <thead>
                                    <tr>
                                        <th>{{ __('No') }}</th>
                                        <th>{{ __('NIS') }}</th>
                                        <th>{{ __('Nama') }}</th>
                                        <th>{{ __('Gender') }}</th>
                                        <th>{{ __('Kategori') }}</th>
                                        <th>{{ __('Kelas') }}</th>
                                        <th>{{ __('NISN/Username') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('No Telepon') }}</th>
                                        <th>{{ __('Role') }}</th>
                                        <th>{{ __('Aksi') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($users as $item)
                                        <tr>
                                            <td>
                                                {{ $no++ }}
                                            </td>
                                            <td>
                                                {{ $item->nis }}
                                            </td>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td>
                                                {{ $item->gender }}
                                            </td>
                                            <td>
                                                {{ $item->categories->category_name }}
                                            </td>
                                            <td>
                                                @if ($item->classes == null)
                                                @else
                                                    {{ $item->classes->class_name }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->email }}
                                            </td>
                                            <td>
                                                {{ $item->user_email || '-' }}
                                            </td>
                                            <td>
                                                {{ $item->number || '-' }}
                                            </td>
                                            <td>
                                                @foreach ($item->roles as $role)
                                                    <span class="font-weight-bold text-primary">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('access-userEdit')
                                                        <a href="{{ url('account/users/edit/' . $item->id) }}">
                                                            <div class="text-warning mx-2 cursor-pointer">
                                                                <i class="fas fa-pen" title="Edit User"></i>
                                                            </div>
                                                        </a>
                                                    @endcan
                                                    @can('access-userDelete')
                                                        <div class="text-danger mx-2 cursor-pointer">
                                                            <i class="fas data-delete fa-trash-alt user-delete"
                                                                data-card-id="{{ $item->id }}" title="Delete User"></i>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </td>
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


    @can('access-userAdd')
        <div class="modal fade" tabindex="-1" role="dialog" id="PenggunaModal">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="p-4">
                        <form id="addForm">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{ __('Nama Lengkap') }} <span class="text-danger">*</span></label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" placeholder="My Full Name" required
                                    autocomplete="name" autofocus>
                            </div>
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="nis">{{ __('NIS') }} <span class="text-danger">*</span></label>
                                    <input id="nis" type="text" class="form-control @error('nis') is-invalid @enderror"
                                        name="nis" value="{{ old('nis') }}" placeholder="992883" required
                                        autocomplete="nis" autofocus>
                                </div>
                                <div class="form-group col-6">
                                    <label for="gender">{{ __('Gender') }} <span class="text-danger">*</span></label>
                                    <select class="form-control" name="gender" required>
                                        <option selected value="">{{ __('-- Pilih Gender --') }}</option>
                                        <option value="Laki-Laki">{{ __('Laki-Laki') }}</option>
                                        <option value="Perempuan">{{ __('Perempuan') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="nis">{{ __('Kelas') }} <span class="text-danger">*</span></label>
                                    <select class="form-control" name="class_id" required>
                                        <option selected value="">{{ __('-- Pilih Kelas --') }}</option>
                                        @foreach ($classes as $item)
                                            <option value="{{ $item->id }}">{{ $item->class_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label for="gender">{{ __('Kategori') }} <span class="text-danger">*</span></label>
                                    <select class="form-control" name="category_id" required>
                                        <option selected value="">{{ __('-- Pilih Kategori --') }}</option>
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="role">{{ __('Role') }} <span class="text-danger">*</span></label>
                                <select class="form-control" name="role_id" required>
                                    <option selected value="">{{ __('-- Pilih  Role --') }}</option>
                                    @foreach ($roles as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="email">{{ __('NISN/Username') }} <span class="text-danger">*</span></label>
                                <input id="email" type="text"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" placeholder="112233445566" required autocomplete="email">
                            </div>
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="user_email">{{ __('Email') }} </label>
                                    <input id="user_email" type="text"
                                        class="form-control @error('user_email') is-invalid @enderror" name="user_email"
                                        value="{{ old('user_email') }}" placeholder="example@gmail.com"
                                        autocomplete="user_email">
                                </div>
                                <div class="form-group col-6">
                                    <label for="number">{{ __('No Telepon') }} </label>
                                    <input id="number" type="text"
                                        class="form-control @error('number') is-invalid @enderror" name="number"
                                        value="{{ old('number') }}" placeholder="0877xxxxxxxx" autocomplete="number">
                                </div>
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
                                    <input class="custom-control-input" type="checkbox" name="remember" id="show-password">
                                    <label class="custom-control-label" for="show-password">{{ __('Show Password') }}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    {{ __('Tambah Data') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="tambahPenggunaBulking">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Pengguna Dengan Excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="p-4">
                        <form id="uploadExcelForm" action="{{ route('import.users') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="excelFile">{{ __('Upload Excel File') }} <span
                                        class="text-danger">*</span></label>
                                <input id="excelFile" type="file"
                                    class="form-control @error('excelFile') is-invalid @enderror" name="excelFile"
                                    accept=".xls,.xlsx" required>
                                @error('excelFile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    {{ __('Upload File') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    @endcan
@endsection

@push('scripts')

    @can('access-userAdd')
        <script>
            const passwordField = document.getElementById("password");
            const confirmPasswordField = document.getElementById("password-confirm");
            const showPasswordCheckbox = document.getElementById("show-password");

            showPasswordCheckbox.addEventListener("change", function() {
                if (showPasswordCheckbox.checked) {
                    passwordField.type = "text";
                    confirmPasswordField.type = "text";
                } else {
                    passwordField.type = "password";
                    confirmPasswordField.type = "password";
                }
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const addForm = document.getElementById('addForm');
                addForm.addEventListener('submit', async function(event) {
                    event.preventDefault();
                    const formData = new FormData(addForm);
                    const attributeData = {};
                    formData.forEach((value, key) => {
                        attributeData[key] = value;
                    });

                    try {
                        const response = await fetch(`/account/users/add`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(attributeData)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            displayErrors(errorData.errors);
                        } else {
                            Notiflix.Notify.success('Data user berhasil dibuat!');
                            location.reload();
                        }
                    } catch (error) {
                        Notiflix.Notify.failure('Error:',
                            'An error occurred while processing the request.');
                    }
                });

                function displayErrors(errors) {
                    const errorMessages = Object.values(errors).map(error => error.join('\n')).join('\n');
                    Notiflix.Notify.failure(errorMessages);
                }
            });
        </script>
    @endcan

    @can('access-userDelete')
        <script>
            const deleteUser = document.querySelectorAll('.user-delete');

            deleteUser.forEach(button => {
                button.addEventListener('click', function() {
                    const cardId = button.dataset.cardId;

                    Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                        'Batal',
                        function() {
                            fetch(`/account/users/delete/${cardId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Notiflix.Notify.success("Data user berhasil dihapus!", {
                                        timeout: 3000
                                    });
                                    location.reload();
                                })
                                .catch(error => {
                                    Notiflix.Notify.failure('Error:', error);
                                });
                        });
                });
            });
        </script>
    @endcan

    @can('access-userList')
        <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
        <script>
            $("#table-Pengguna").dataTable();
        </script>
    @endcan

@endpush
