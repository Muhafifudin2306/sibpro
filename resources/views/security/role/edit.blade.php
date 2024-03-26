@extends('layouts.admin.app')

@section('title_page', 'Edit Role')

@section('content')

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Edit Roles') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('Pengaturan Akun') }}</div>
                        <div class="breadcrumb-item">{{ __('Manajemen Roles') }}</div>
                        <div class="breadcrumb-item active">{{ __('Edit') }}</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>{{ __('Edit Role') }}</h4>
                        <a href="{{ url('/account/security/role') }}"> {{ __('Close') }} </a>
                    </div>
                    <div class="card-body pb-5">
                        <form class="update-form" data-action="{{ url('account/security/role/update/' . $role->id) }} }}"
                            method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{ __('Nama Role') }}</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    value="{{ $role->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="permission_id">{{ __('Permission') }}</label>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="py-1 mx-3">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="select-all">
                                                <label class="custom-control-label" for="select-all">Pilih Semua</label>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach ($allPermissions as $item)
                                        <div class="col-6 col-md-3">
                                            <div class="py-1 mx-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox"
                                                        name="permission_id[]" id="{{ $item->id }}"
                                                        value="{{ $item->id }}"
                                                        {{ in_array($item->id, $permissions->pluck('id')->toArray()) ? 'checked' : '' }}>
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
    @can('access-roleUpdate')
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
                                Notiflix.Notify.success("Data role berhasil diperbarui!", {
                                    timeout: 3000
                                });

                                location.href = "{{ url('account/security/role') }}";
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
