@extends('layouts.admin.app')

@section('title_page', 'Permission List')

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
                    <h1>{{ __('Permissions') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('Account') }}</div>
                        <div class="breadcrumb-item active">{{ __('Permissions') }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Data Permissions') }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih dan Tambah Data Permissions') }}
                        </p>
                    </div>
                    @can('access-permissionAdd')
                        <div class="action-content">
                            <button class="btn btn-primary" data-toggle="modal"
                                data-target="#addModal">{{ __('+ Tambah Data') }}</button>
                        </div>
                    @endcan
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="title-class">
                                    <h6> {{ __('Tabel Permission') }} </h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-permissions">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center">{{ __('No') }}</th>
                                            <th>{{ __('Nama') }}</th>
                                            <th>{{ __('Guard Name') }}</th>
                                            <th>{{ __('Diedit Pada') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                            $modal = 1;
                                        @endphp
                                        @foreach ($permissions as $item)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $no++ }}
                                                </td>
                                                <td>
                                                    {{ $item->name }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->guard_name }}
                                                </td>
                                                <td>
                                                    {{ $item->updated_at->format('d F Y') }}
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        @can('access-permissionUpdate')
                                                            <div class="text-warning mx-2 cursor-pointer" data-toggle="modal"
                                                                data-target="#updateModal{{ $item->id }}">
                                                                <i class="fas fa-pen" title="Edit Permission"></i>
                                                            </div>
                                                        @endcan
                                                        @can('access-permissionDelete')
                                                            <div class="text-danger mx-2 cursor-pointer permission-delete"
                                                                data-card-id="{{ $item->id }}">
                                                                <i class="fas data-delete fa-trash-alt"
                                                                    title="Delete Permission"></i>
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
    @can('access-permissionAdd')
        <div class="modal fade" tabindex="-1" role="dialog" id="addModal">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Tambah Data Permission') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">{{ __('Nama Permission') }}</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="access-classList" required="">
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
    @can('access-permissionUpdate')
        @foreach ($permissions as $item)
            <div class="modal fade" tabindex="-1" role="dialog" id="updateModal{{ $item->id }}">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Tambah Data Permission') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="update-form" data-action="{{ url('account/security/permission/update/' . $item->id) }} }}"
                            method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">{{ __('Nama Permission') }}</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ $item->name }}">
                                </div>
                            </div>
                            <div class="modal-footer bg-whitesmoke br">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" class="btn btn-primary">{{ __('Simpan Data') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endcan
@endsection

@push('scripts')
    @can('access-permissionAdd')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const addForm = document.getElementById('addForm');
                addForm.addEventListener('submit', async function(event) {
                    event.preventDefault();
                    const formData = new FormData(addForm);
                    const attributeData = {};
                    formData.forEach((value, key) => {
                        attributeData[key] = value;
                    });

                    try {
                        const response = await fetch(`/account/security/permission/add`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(attributeData)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            const errorMessages = Object.values(errorData.errors).join('\n');
                            Notiflix.Notify.failure(
                                'Field tidak boleh kosong atau nama sejenis telah digunakan');
                        } else {
                            Notiflix.Notify.success('Data permission berhasil dibuat!');
                            location.reload();
                        }
                    } catch (error) {
                        Notiflix.Notify.failure('Error:',
                            'An error occurred while processing the request.');
                    }
                });
            });
        </script>
    @endcan

    @can('access-permissionDelete')
        <script>
            const deletePermission = document.querySelectorAll('.permission-delete');

            deletePermission.forEach(button => {
                button.addEventListener('click', function() {
                    const cardId = button.dataset.cardId;

                    Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                        'Batal',
                        function() {
                            fetch(`/account/security/permission/delete/${cardId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Notiflix.Notify.success("Data permission berhasil dihapus!", {
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

    @can('access-permissionUpdate', $post)
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
                                Notiflix.Notify.success("Data permission berhasil diperbarui!", {
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

    @can('access-permissionList')
        <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
        <script>
            $("#table-permissions").dataTable();
        </script>
    @endcan
@endpush
