@extends('layouts.admin.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">

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
                        <div class="breadcrumb-item">Kelas</div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">Data Kelas</h2>
                        <p class="section-lead">
                            Pilih dan Tambah Data Kelas
                        </p>
                    </div>
                    <div class="action-content">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">+ Tambah
                            Data</button>
                    </div>
                </div>


                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Tabel Kelas') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-tagihan-vendor">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center">
                                                No
                                            </th>
                                            <th>Nama Kelas</th>
                                            <th>Diubah pada</th>
                                            <th>Petugas</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($classes as $item)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $no++ }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->class_name }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->updated_at->format('d F Y') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->users->name }}
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <div class="text-warning mx-2 cursor-pointer" data-toggle="modal"
                                                            data-target="#exampleModal{{ $item->id }}">
                                                            <i class="fas fa-pen" title="Edit Nama Kelas"></i>
                                                        </div>
                                                        <div class="text-danger mx-2 cursor-pointer">
                                                            <i class="fas class-delete fa-trash-alt"
                                                                data-card-id="{{ $item->id }}"
                                                                title="Delete Kelas"></i>
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
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                Development by Muhammad Afifudin</a>
            </div>
            <div class="footer-right">

            </div>
        </footer>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="classForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="class_name">Nama Kelas</label>
                            <input type="text" class="form-control" name="class_name" id="class_name"
                                placeholder="X TKJ A/X TKJ B" autofocus>
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
    @foreach ($classes as $item)
        <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal{{ $item->id }}">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Data Kelas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="update-form" data-action="{{ url('/setting/class/update/' . $item->id) }} }}"
                        method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="class_name">Nama Kelas</label>
                                <input type="text" class="form-control" name="class_name" id="class_name"
                                    value="{{ $item->class_name }}">
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

        document.addEventListener('DOMContentLoaded', function() {
            // Tangkap form input tahun
            const classForm = document.getElementById('classForm');

            // Tambahkan event listener untuk saat form disubmit
            classForm.addEventListener('submit', function(event) {
                event.preventDefault();

                // Ambil data dari form input tahun
                const formData = new FormData(classForm);
                const classData = {};
                formData.forEach((value, key) => {
                    classData[key] = value;
                });

                // Kirim permintaan AJAX ke endpoint untuk menyimpan data tahun baru
                fetch(`/setting/class/add`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(classData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Tampilkan notifikasi sukses menggunakan Notiflix
                        Notiflix.Notify.success("Data Kelas berhasil ditambahkan", {
                            timeout: 3000 // Waktu dalam milidetik (3 detik dalam contoh ini)
                        });

                        // Refresh halaman saat ini
                        location.reload();
                    })
                    .catch(error => {
                        // Tampilkan notifikasi error menggunakan Notiflix
                        Notiflix.Notify.failure('Error:', error);
                    });
            });
        });

        const deleteClass = document.querySelectorAll('.class-delete');

        // Tambahkan event listener untuk setiap tombol "Hapus Data"
        deleteClass.forEach(button => {
            button.addEventListener('click', function() {
                const cardId = button.dataset.cardId;

                Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                    'Batal',
                    function() {
                        fetch(`/setting/class/delete/${cardId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Tampilkan notifikasi sukses menggunakan Notiflix
                                Notiflix.Notify.success("Data Kelas berhasil dihapus.", {
                                    timeout: 3000 // Waktu dalam milidetik (3 detik dalam contoh ini)
                                });

                                // Refresh halaman saat ini
                                location.reload();
                            })
                            .catch(error => {
                                // Tampilkan notifikasi error menggunakan Notiflix
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
    <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
@endsection
