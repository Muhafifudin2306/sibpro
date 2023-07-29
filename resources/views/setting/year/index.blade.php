@extends('layouts.admin.app')

@section('content')
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Tahun Aktif') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active">Dashboard</div>
                        <div class="breadcrumb-item active">General Setting</div>
                        <div class="breadcrumb-item">Tahun Aktif</div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">Tahun Pembelajaran Aktif</h2>
                        <p class="section-lead">
                            Pilih dan Tambah Data Tahun Pembelajaran Aktif
                        </p>
                    </div>
                    <div class="action-content">
                        <button class="btn btn-primary">+ Tambah Data</button>
                    </div>
                </div>

                <div class="row">
                    @foreach ($years as $year)
                        @if ($year->year_status == 'active')
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h4>Aktif</h4>
                                    </div>
                                    <div class="card-body">
                                        <p>Tahun Pelajaran {{ $year->year_name }}</p>
                                    </div>
                                </div>
                            </div>
                        @elseif ($year->year_status == 'nonActive')
                            <div class="col-12 col-md-6 col-lg-3">
                                <div id="{{ $year->id }}" class="card card-danger cursor-pointer"
                                    data-card-id="{{ $year->id }}">
                                    <div class="card-header">
                                        <h4>Non Aktif</h4>
                                    </div>
                                    <div class="card-body">
                                        <p>Tahun Pelajaran {{ $year->year_name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <script>
                        // Tangkap semua tombol "Ubah Status"
                        const updateButtons = document.querySelectorAll('.card-danger');

                        // Tambahkan event listener untuk setiap tombol
                        updateButtons.forEach(button => {
                            button.addEventListener('click', function() {
                                // Ambil ID card dari atribut data-card-id
                                const cardId = button.dataset.cardId;

                                // Kirim permintaan AJAX ke endpoint update-status
                                fetch(`/setting/year/${cardId}`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Content-Type': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {

                                        Notiflix.Notify.success("Data Berhasil Diperbarui", {
                                            timeout: 3000 // Waktu dalam milidetik (3 detik dalam contoh ini)
                                        });

                                        // Refresh halaman saat ini
                                        location.reload();
                                    })
                                    .catch(error => {
                                        Notiflix.Notify.failure('Error:', error);
                                    });
                            });
                        });
                    </script>

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
@endsection
