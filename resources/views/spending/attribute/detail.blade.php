@extends('layouts.admin.app')

@section('title_page')
    Realisasi Dana Atribut
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
                    <h1>Realisasi Dana Atribut</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item active">{{ __('Pengeluaran') }}</div>
                        <div class="breadcrumb-item">{{ __('Atribut') }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Realisasi') }} {{ $attribute->attribute_name }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih dan Edit Data Realisasi Atribut') }}
                        </p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-success">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Debit</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <h5>Rp77.000.000</h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-danger">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Kredit</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <h5>Rp47.000.000</h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-primary">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Sisa Saldo</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <h5>Rp30.000.000</h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-warning">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Hutang</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <h5>Rp5.000.000</h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Tabel Debit') }}</h4>
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
                                        {{-- @php
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
                                        @endforeach --}}
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
        {{-- <div class="modal fade" tabindex="-1" role="dialog" id="updateAll">
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
        </div> --}}
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
