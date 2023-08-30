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

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Pembayaran SPP</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active">Dashboard</div>
                        <div class="breadcrumb-item active">Pemasukan</div>
                        <div class="breadcrumb-item">SPP</div>
                    </div>
                </div>

                <div class="section-body">

                    <h2 class="section-title">Pembayaran SPP Siswa</h2>
                    <p class="section-lead">
                        Pilih kelas untuk mengetahui info lebih lanjut
                    </p>

                    <div class="row">
                        @foreach ($studentClasses as $item)
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="card card-primary">
                                    <div class="card-header-card">
                                        <h6 class="text-center p-4 border-bottom">{{ $item->class_name }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <a href="{{ url('income/credit/detail/' . $item->id) }}">
                                            <button class="btn btn-primary w-100">More Detail</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
