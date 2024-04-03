@extends('layouts.admin.app')

@section('title_page', 'Petugas List')

@section('content')
    @push('styles')
        @can('access-classList')
            <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
        @endcan
    @endpush

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin :students="$students"></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Master Petugas') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item active">{{ __('Master Petugas') }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Data Petugas') }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih dan Tambah Data Petugas') }}
                        </p>
                    </div>
                    <div class="action-content">
                        @can('access-classAdd')
                            <button class="btn btn-primary" data-toggle="modal" data-target="#AddModal"><i
                                    class="fas fa-plus mx-1"></i> {{ __('Tambah Data') }}</button>
                        @endcan
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Tabel Petugas') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-tagihan-vendor">
                                <thead>
                                    <tr>
                                        <th style="width:10px">{{ __('No') }}</th>
                                        <th>{{ __('Nama Petugas') }}</th>
                                        <th>{{ __('Diubah pada') }}</th>
                                        <th>{{ __('Aksi') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($petugas as $item)
                                        <tr>
                                            <td>
                                                {{ $no++ }}
                                            </td>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td>
                                                {{ $item->updated_at->format('d F Y') }}
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-start">
                                                    @can('access-petugasUpdate')
                                                        <div class="text-warning mx-2 cursor-pointer" data-toggle="modal"
                                                            data-target="#updateModal{{ $item->id }}">
                                                            <i class="fas fa-pen" title="Edit Nama Petugas"></i>
                                                        </div>
                                                    @endcan
                                                    {{-- @can('access-petugasDelete') --}}
                                                    <div class="text-danger mx-2 cursor-pointer">
                                                        <i class="fas class-delete fa-trash-alt"
                                                            data-card-id="{{ $item->id }}" title="Delete Petugas"></i>
                                                    </div>
                                                    {{-- @endcan --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                        <h5 class="modal-title">Tambah Data Petugas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="{{ route('storePetugas') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Nama Petugas</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Masukkan nama petugas" required>
                            </div>
                            <div class="form-group">
                                <label for="image">Tanda Tangan Petugas</label>
                                <input type="file" class="form-control" required name="image">
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
        @foreach ($petugas as $item)
            <div class="modal fade" tabindex="-1" role="dialog" id="updateModal{{ $item->id }}">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Data Petugas</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="update-form" data-action="{{ url('/class/update/' . $item->id) }} }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">Nama Petugas</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ $item->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="signature">Tanda Tangan Petugas</label>
                                    <input type="file" class="form-control" required name="signature">
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
        {{-- @can('access-petugasDelete') --}}
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
                            fetch(`/petugas/delete/${cardId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Notiflix.Notify.success("Data Petugas berhasil dihapus!", {
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
        {{-- @endcan --}}

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
                                    Notiflix.Notify.success("Data Petugas berhasil diperbarui!", {
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

        @can('access-classList')
            <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
            <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
        @endcan
    @endpush
@endsection
