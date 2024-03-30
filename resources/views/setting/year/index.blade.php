@extends('layouts.admin.app')

@section('title_page', 'Year List')

@section('content')
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin :students="$studentSide"></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Tahun Aktif') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('General Setting') }}</div>
                        <div class="breadcrumb-item active">{{ __('Tahun Aktif') }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Tahun Pembelajaran Aktif') }}</h2>
                        @can('access-yearAdd')
                            <p class="section-lead">
                                {{ __('Pilih dan Tambah Data Tahun Pembelajaran Aktif') }}
                            </p>
                        @endcan
                    </div>
                    @can('access-yearAdd')
                        <div class="action-content">
                            <button class="btn btn-primary" data-toggle="modal"
                                data-target="#exampleModal">{{ __('+ Tambah Data') }}</button>
                        </div>
                    @endcan
                </div>
                <div class="row">
                    @foreach ($years as $year)
                        @if ($year->year_status == 'active')
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h4>{{ 'Tahun Aktif' }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <p>Semester {{ $year->year_semester }}</p>
                                        <p>Tahun Pelajaran {{ $year->year_name }}</p>
                                    </div>
                                </div>
                            </div>
                        @elseif ($year->year_status == 'nonActive')
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card card-danger" title="Aktifkan Tahun Pelajaran">
                                    <div class="card-header d-flex justify-content-between">
                                        <h4>{{ 'Non Aktif' }}</h4>
                                        @can('access-yearDelete')
                                            <i class="fas fa-trash card-delete cursor-pointer text-danger"
                                                data-card-id="{{ $year->id }}" title="Hapus Tahun Pelajaran"></i>
                                        @endcan
                                    </div>
                                    <div class="card-body cursor-pointer card-body-off" data-card-id="{{ $year->id }}">
                                        <p>Semester {{ $year->year_semester }}</p>
                                        <p>Tahun Pelajaran {{ $year->year_name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                {{ __('Development by Muhammad Afifudin') }}</a>
            </div>
        </footer>
    </div>
    @can('access-yearAdd')
        <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Tahun</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="yearForm">
                        <div class="modal-body">
                            <div class="row pt-3 pb-1">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="year_name">Nama Tahun Pelajaran</label>
                                        <input type="text" id="year_name" class="form-control" name="year_name"
                                            placeholder="2022/2023" required autofocus>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="year_semester">Nama Semester</label>
                                        <select name="year_semester" id="year_semester" class="form-control">
                                            <option selected disabled> -- Pilih Semester -- </option>
                                            <option value="Genap">Genap</option>
                                            <option value="Ganjil">Ganjil</option>
                                        </select>
                                    </div>
                                </div>
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
    @endcan

    @push('scripts')
        @can('access-yearAdd')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const yearForm = document.getElementById('yearForm');
                    yearForm.addEventListener('submit', async function(event) {
                        event.preventDefault();
                        const formData = new FormData(yearForm);
                        const yearData = {};
                        formData.forEach((value, key) => {
                            yearData[key] = value;
                        });

                        const response = await fetch(`/setting/year/add`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(yearData)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            if (errorData.hasOwnProperty('errors')) {
                                const errorMessages = Object.values(errorData.errors).join('\n');
                                Notiflix.Notify.failure(
                                    'Error: Nama Tahun tidak boleh kosong atau nama sejenis telah digunakan', {
                                        timeout: 3000
                                    });
                            } else {
                                Notiflix.Notify.failure('Error:',
                                    'Terjadi kesalahan saat memproses permintaan.');
                            }
                        } else {
                            const responseData = await response.json();
                            Notiflix.Notify.success("Data tahun berhasil ditambahkan!", {
                                timeout: 3000
                            });
                            location.reload();
                        }
                    });
                });
            </script>
        @endcan

        @can('access-yearDelete')
            <script>
                const deleteButtons = document.querySelectorAll('.card-delete');
            </script>
            <script>
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const cardId = button.dataset.cardId;

                        Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                            'Batal',
                            function() {
                                fetch(`/setting/year/delete/${cardId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        Notiflix.Notify.success("Data tahun berhasil dihapus!", {
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

        @can('access-yearUpdate')
            <script>
                const updateButtons = document.querySelectorAll('.card-body-off');
            </script>
            <script>
                updateButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const cardId = button.dataset.cardId;

                        Notiflix.Confirm.show(
                            'Konfirmasi',
                            'Apakah Anda yakin ingin memperbarui data ini?',
                            'Ya',
                            'Batal',
                            function() {
                                fetch(`/setting/year/update/${cardId}`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Content-Type': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        Notiflix.Notify.success("Data tahun berhasil diperbarui!", {
                                            timeout: 3000
                                        });
                                        location.reload();
                                    })
                                    .catch(error => {
                                        Notiflix.Notify.failure('Error:', error);
                                    });
                            }
                        );
                    });
                });
            </script>
        @endcan
    @endpush
@endsection
