@extends('layouts.admin.app')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.css') }}">
    @endpush
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Roles') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-role">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-role">{{ __('Account') }}</div>
                        <div class="breadcrumb-role active">{{ __('Roles') }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-roles-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Edit Data Roles') }}</h2>
                    </div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form class="update-form"
                                data-action="{{ url('account/security/role/update/' . $role->id) }} }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">{{ __('Nama Role') }}</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ $role->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="permission_id">{{ __('Akses') }}</label>
                                    <select class="form-control select2" name="permission_id[]" multiple>
                                        @foreach ($allPermissions as $role)
                                            <option value="{{ $role->id }}"
                                                {{ in_array($role->id, $permissions->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">{{ __('Simpan Data') }}</button>
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
    <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>

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
                            Notiflix.Notify.success("Data Berhasil Diperbarui", {
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
@endpush
