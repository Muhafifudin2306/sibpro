@extends('layouts.admin.app')

@section('title_page')
    List of {{ $class->class_name }}
@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ $class->class_name }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item active">{{ __('Siswa') }}</div>
                        <div class="breadcrumb-item">{{ $class->class_name }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Data Siswa') }} {{ $class->class_name }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih dan Edit Data Siswa') }}
                        </p>
                    </div>
                    <div class="action-content">
                        @can('access-studentUpdate')
                            <button id="updateAllBtn" class="btn btn-primary" data-toggle="modal"
                                data-target="#updateAll">{{ __('Ubah Kelas') }}</button>
                        @endcan
                        @can('access-studentDelete')
                            <button id="deleteAllBtn" data-student-id="{{ $class->id }}"
                                class="btn btn-danger all-student-delete">{{ __('Delete Semua') }}</button>
                        @endcan
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Tabel Siswa') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-student">
                                    <thead>
                                        <tr>
                                            <th>{{ __('No') }}</th>
                                            <th>{{ __('NIS') }}</th>
                                            <th>{{ __('Nama Siswa') }}</th>
                                            <th>{{ __('Gender') }}</th>
                                            <th>{{ __('Kategori') }}</th>
                                            <th>{{ __('Email') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($students as $item)
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
                                                    {{ $item->email }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                {{ __('Development by Muhammad Afifudin') }}
            </div>
        </footer>
    </div>

    @can('access-studentUpdate')
        <div class="modal fade" tabindex="-1" role="dialog" id="updateAll">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Edit Kelas Siswa') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="update-form" data-action="{{ url('setting/student/update/allClass/' . $class->id) }} }}"
                        method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nis">{{ __('Kelas') }} <span class="text-danger">*</span></label>
                                <select class="form-control" name="class_id">
                                    <option disabled>{{ __('-- Pilih Kelas --') }}</option>
                                    @foreach ($classes as $item)
                                        <option value="{{ $item->id }}" {{ $class->id == $item->id ? 'selected' : '' }}>
                                            {{ $item->class_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Simpan Data') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection

@push('scripts')
    <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>

    @can('access-studentUpdate')
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
                                Notiflix.Notify.success("Data berhasil diperbarui!", {
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

    @can('access-studentDelete')
        <script>
            const deleteStudent = document.querySelectorAll('.all-student-delete');

            deleteStudent.forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = button.dataset.studentId;

                    Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                        'Batal',
                        function() {
                            fetch(`/setting/student/delete/allStudent/${studentId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Notiflix.Notify.success(
                                        "Data siswa berhasil dihapus!", {
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

    <script>
        $("#table-student").dataTable();
    </script>
@endpush
