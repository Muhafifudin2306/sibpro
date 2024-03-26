@extends('layouts.admin.app')

@section('title_page', 'Add Role');

@section('content')
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Tambah Roles') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('Pengaturan Akun') }}</div>
                        <div class="breadcrumb-item">{{ __('Manajemen Roles') }}</div>
                        <div class="breadcrumb-item active">{{ __('Tambah') }}</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>{{ __('Tambah Roles') }}</h4>
                        <a href="{{ url('/account/security/role') }}"> {{ __('Close') }} </a>
                    </div>
                    <div class="card-body pb-5">
                        <form class="store-form" data-action="{{ url('/account/security/role/store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>{{ __('Nama Role') }}</label>
                                <input type="text" class="form-control" name="roleName" placeholder="Masukkan Nama Role"
                                    required>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Permission') }}</label>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="py-1 mx-3">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="select-all">
                                                <label class="custom-control-label" for="select-all">Pilih Semua</label>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach ($permissions as $item)
                                        <div class="col-6 col-md-3">
                                            <div class="py-1 mx-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox"
                                                        name="permission_id[]" id="{{ $item->id }}"
                                                        value="{{ $item->id }}">
                                                    <label class="custom-control-label"
                                                        for="{{ $item->id }}">{{ $item->name }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">{{ __('Simpan Data') }}</button>
                        </form>
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
            var selectAllCheckbox = document.getElementById('select-all');

            var permissionCheckboxes = document.querySelectorAll('input[name="permission_id[]"]');
            function toggleAllPermissions(checked) {
                permissionCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = checked;
                });
            }

            selectAllCheckbox.addEventListener('change', function() {
                toggleAllPermissions(this.checked);
            });
        });
    </script>

    @can('access-roleAdd')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const updateForms = document.querySelectorAll('.store-form');

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
                                Notiflix.Notify.success("Data roles berhasil dibuat!", {
                                    timeout: 3000
                                });

                                location.href = "{{ url('/account/security/role') }}";
                            })
                            .catch(error => {
                                Notiflix.Notify.failure('Error:', error);
                            });
                    });
                });
            });
        </script>
    @endcan
@endpush
