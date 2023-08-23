@extends('layouts.admin.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.css') }}">

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Data Kelas') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active">Dashboard</div>
                        <div class="breadcrumb-item active">General Setting</div>
                        <div class="breadcrumb-item">Siswa</div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">Data Siswa</h2>
                        <p class="section-lead">
                            Pilih dan Tambah Data Siswa
                        </p>
                    </div>
                    <div class="action-content">
                        <a href="{{ route('addStudent') }}">
                            <button class="btn btn-primary">+ Tambah
                                Data</button>
                        </a>
                    </div>
                </div>
                @foreach ($classes as $class)
                    <div class="col-12">
                        <div class="card">
                            <div class="p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="title-class">
                                        <h6> Tabel Siswa {{ $class->class_name }}</h6>
                                    </div>
                                    <div class="action-content">
                                        <button class="btn btn-warning mx-1" data-toggle="modal"
                                            data-target="#classModal{{ $class->id }}"><i class="fas fa-pen"
                                                title="Edit Siswa"></i>
                                            Edit Semua Kelas</button>
                                        <button class="btn btn-danger mx-1 data-student-delete"
                                            data-card-id="{{ $class->id }}"><i class="fas fa-trash-alt"
                                                title="Delete Siswa"></i> Hapus Semua
                                            Siswa</button>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-siswa-{{ $class->id }}">
                                        <thead>
                                            <tr class="text-center">
                                                <th class="text-center">
                                                    No
                                                </th>
                                                <th>NIS</th>
                                                <th>Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Kategori</th>
                                                <th>Diubah pada</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($class->students as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $no++ }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $item->nis }}
                                                    </td>
                                                    <td>
                                                        {{ $item->student_name }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $class->class_name }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $item->categories->category_name }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $item->updated_at->format('d F Y') }}
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <div class="text-warning mx-2 cursor-pointer"
                                                                data-toggle="modal"
                                                                data-target="#studentModal{{ $item->id }}">
                                                                <i class="fas fa-pen" title="Edit Siswa"></i>
                                                            </div>
                                                            <div class="text-danger mx-2 cursor-pointer">
                                                                <i class="fas data-delete fa-trash-alt"
                                                                    data-card-id="{{ $item->id }}"
                                                                    title="Delete Siswa"></i>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </section>
        </div>

        <footer class="main-footer">
            <div class="footer-left">
                Development by Muhammad Afifudin</a>
            </div>
        </footer>
    </div>
    {{-- Add Modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="studentModal">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Atribut</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="attributeForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="attribute_name">Nama Atribut</label>
                            <input type="text" class="form-control" name="attribute_name" id="attribute_name"
                                placeholder="Topi/Dasi/Seragam" autofocus>
                        </div>
                        <div class="form-group">
                            <label>Select2</label>
                            <select class="form-control select2">
                                <option>Option 1</option>
                                <option>Option 2</option>
                                <option>Option 3</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Student Edit Modal --}}

    @foreach ($classes as $class)
        @foreach ($class->students as $item)
            <div class="modal fade" tabindex="-1" role="dialog" id="studentModal{{ $item->id }}">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Data Siswa</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="update-form" data-action="{{ url('/setting/student/update/' . $item->id) }}"
                            method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nis">NIS</label>
                                    <input type="text" class="form-control" name="nis" id="nis"
                                        value="{{ $item->nis }}" autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="student_name">Nama Siswa</label>
                                    <input type="text" class="form-control" name="student_name" id="student_name"
                                        value="{{ $item->student_name }}" autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="student_name">Kategori</label>
                                    <select class="form-control select2" name="category_id">
                                        <option>-- Pilih --</option>
                                        @foreach ($category as $part)
                                            <option value="{{ $part->id }}"
                                                {{ $item->category_id == $part->id ? 'selected' : '' }}>
                                                {{ $part->category_name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="student_name">Kelas</label>
                                    <select class="form-control select2" name="class_id">
                                        <option>-- Pilih Kelas --</option>
                                        @foreach ($classList as $part)
                                            <option value="{{ $part->id }}"
                                                {{ $item->class_id == $part->id ? 'selected' : '' }}>
                                                {{ $part->class_name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer bg-whitesmoke br">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

    {{-- Class Edit Modal --}}
    @foreach ($classes as $class)
        <div class="modal fade" tabindex="-1" role="dialog" id="classModal{{ $class->id }}">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Data Kelas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="update-form" data-action="{{ url('/setting/student/update/allClass/' . $class->id) }}"
                        method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="student_name">Kelas</label>
                                <select class="form-control select2" name="class_id">
                                    <option>-- Pilih Kelas --</option>
                                    @foreach ($classList as $part)
                                        <option value="{{ $part->id }}">
                                            {{ $part->class_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

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

                            location.reload();
                        })
                        .catch(error => {
                            Notiflix.Notify.failure('Error:', error);
                        });
                });
            });
        });
        // Delete Data Student
        const deleteButtons = document.querySelectorAll('.data-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const cardId = button.dataset.cardId;

                Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                    'Batal',
                    function() {
                        fetch(`/setting/student/delete/${cardId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                Notiflix.Notify.success("Data Siswa berhasil dihapus.", {
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

        const deleteStudent = document.querySelectorAll('.data-student-delete');

        deleteStudent.forEach(button => {
            button.addEventListener('click', function() {
                const cardId = button.dataset.cardId;

                Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                    'Batal',
                    function() {
                        fetch(`/setting/student/delete/allStudent/${cardId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                Notiflix.Notify.success("Data Siswa berhasil dihapus.", {
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
@endsection

@section('script')
    <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/modules/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
    @foreach ($classes as $class)
        <script>
            "use strict";
            $("#table-siswa-" + "{{ $class->id }}").dataTable();
        </script>
    @endforeach
@endsection
