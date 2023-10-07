@extends('layouts.admin.app')

@section('title_page', 'Class List')

@section('content')
    @push('styles')
        @can('access-classList')
            <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
        @endcan
    @endpush

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Kelas') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('General Setting') }}</div>
                        <div class="breadcrumb-item active">{{ __('Kelas') }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Data Kelas') }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih dan Tambah Data Kelas') }}
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
                            <h4>{{ __('Tabel Kelas') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-tagihan-vendor">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center">
                                                {{ __('No') }}
                                            </th>
                                            <th>{{ __('Nama Kelas') }}</th>
                                            <th>{{ __('Diubah pada') }}</th>
                                            <th>{{ __('Petugas') }}</th>
                                            <th>{{ __('Action') }}</th>
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
    @can('access-classAdd')
        <div class="modal fade" tabindex="-1" role="dialog" id="AddModal">
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
    @endcan

    @can('access-classUpdate')
        @foreach ($classes as $item)
            <div class="modal fade" tabindex="-1" role="dialog" id="updateModal{{ $item->id }}">
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
    @endcan

    @push('scripts')
        @can('access-classAdd')
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
                                Notiflix.Notify.success("Data kelas berhasil dibuat!", {
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
        @endcan

        @can('access-classDelete')
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
                                fetch(`/setting/class/delete/${cardId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        Notiflix.Notify.success("Data kelas berhasil dihapus!", {
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

        @can('access-classUpdate')
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
                                    Notiflix.Notify.success("Data kelas berhasil diperbarui!", {
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
    @endpush

    @can('access-classList')
        <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
    @endcan
@endsection
