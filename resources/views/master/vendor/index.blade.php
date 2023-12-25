@extends('layouts.admin.app')

@section('title_page', 'Vendor Data Master')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    @endpush

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Data Master Vendor') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('Master Data') }}</div>
                        <div class="breadcrumb-item active">{{ __('Vendor') }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Master Data Vendor') }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih dan Tambah Master Data Vendor') }}
                        </p>
                    </div>
                    <div class="action-content">
                        @can('access-classAdd')
                            <button class="btn btn-primary" data-toggle="modal"
                                data-target="#AddModal">{{ __('+ Tambah Data') }}</button>
                        @endcan
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Tabel Vendor') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-tagihan-vendor">
                                    <thead>
                                        <tr>
                                            <th>
                                                {{ __('No') }}
                                            </th>
                                            <th>{{ __('Nama Vendor') }}</th>
                                            <th>{{ __('Diubah Pada') }}</th>
                                            <th>{{ __('Aksi') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($vendors as $item)
                                            <tr>
                                                <td>
                                                    {{ $no++ }}
                                                </td>
                                                <td>
                                                    {{ $item->vendor_name }}
                                                </td>
                                                <td>
                                                    {{ $item->updated_at->format('d F Y') }}
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        @can('access-classUpdate')
                                                            <div class="text-warning mx-2 cursor-pointer" data-toggle="modal"
                                                                data-target="#updateModal{{ $item->id }}">
                                                                <i class="fas fa-pen" title="Edit Nama Kelas"></i>
                                                            </div>
                                                        @endcan
                                                        @can('access-classDelete')
                                                            <div class="text-danger mx-2 cursor-pointer">
                                                                <i class="fas class-delete fa-trash-alt"
                                                                    data-card-id="{{ $item->id }}"
                                                                    title="Delete Kelas"></i>
                                                            </div>
                                                        @endcan
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
                {{ __('Development by Muhammad Afifudin') }}</a>
            </div>
        </footer>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="AddModal">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Vendor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="classForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="vendor_name">Nama Vendor</label>
                            <input type="text" class="form-control" name="vendor_name" id="vendor_name"
                                placeholder="Masukkan nama vendor">
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


    @can('access-classUpdate')
        @foreach ($vendors as $item)
            <div class="modal fade" tabindex="-1" role="dialog" id="updateModal{{ $item->id }}">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Data Vendor</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="update-form" data-action="{{ url('/master/vendor/update/' . $item->id) }} }}"
                            method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="vendor_name">Nama Vendor</label>
                                    <input type="text" class="form-control" name="vendor_name" id="vendor_name"
                                        value="{{ $item->vendor_name }}">
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
    @endcan

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const classForm = document.getElementById('classForm');
                classForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const formData = new FormData(classForm);
                    const classData = {};
                    formData.forEach((value, key) => {
                        classData[key] = value;
                    });
                    fetch(`/master/vendor/add`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(classData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            Notiflix.Notify.success("Data vendor berhasil dibuat!", {
                                timeout: 3000
                            });
                            location.reload();
                        })
                        .catch(error => {
                            Notiflix.Notify.failure('Error:', error);
                        });
                });
            });
        </script>


        <script>
            const deleteClass = document.querySelectorAll('.class-delete');
        </script>
        <script>
            deleteClass.forEach(button => {
                button.addEventListener('click', function() {
                    const cardId = button.dataset.cardId;

                    Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                        'Batal',
                        function() {
                            fetch(`/master/vendor/delete/${cardId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Notiflix.Notify.success("Data vendor berhasil dihapus!", {
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

        <script>
            const updateForms = document.querySelectorAll('.update-form');
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

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
                                Notiflix.Notify.success("Data vendor berhasil diperbarui!", {
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

        <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
    @endpush
@endsection
