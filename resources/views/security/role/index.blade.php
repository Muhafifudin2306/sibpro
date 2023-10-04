@extends('layouts.admin.app')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.css') }}">
    @endpush
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Roles') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('Account') }}</div>
                        <div class="breadcrumb-item active">{{ __('Roles') }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Data Roles') }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih dan Tambah Data Roles') }}
                        </p>
                    </div>
                    @can('access-roleAdd')
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
                                    <h6> {{ __('Tabel Roles') }} </h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-users">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center">{{ __('No') }}</th>
                                            <th>{{ __('Nama') }}</th>
                                            <th>{{ __('Guard Name') }}</th>
                                            <th>{{ __('Akses') }}</th>
                                            <th>{{ __('Diedit Pada') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                            $modal = 1;
                                        @endphp
                                        @foreach ($roles as $item)
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
                                                    <div class="d-flex flex-wrap">
                                                        @foreach ($item->permissions as $permission)
                                                            <div class="mb-2 mx-1">
                                                                <button
                                                                    class="btn btn-outline-primary">{{ $permission->name }}</button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ $item->updated_at->format('d F Y') }}
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        @can('access-roleEdit')
                                                            <a href="{{ url('account/security/role/edit/' . $item->id) }}">
                                                                <div class="text-warning mx-2 cursor-pointer">
                                                                    <i class="fas fa-pen" title="Edit Siswa"></i>
                                                                </div>
                                                            </a>
                                                        @endcan
                                                        @can('access-roleDelete')
                                                            <div class="text-danger mx-2 cursor-pointer">
                                                                <i class="fas data-delete fa-trash-alt role-delete"
                                                                    data-card-id="{{ $item->id }}"
                                                                    title="Delete Siswa"></i>
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
    @can('access-roleAdd')
        <div class="modal fade" tabindex="-1" role="dialog" id="addModal">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Tambah Data Role') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">{{ __('Nama Role') }}</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Student"
                                    required="">
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
@endsection

@push('scripts')
    <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/modules/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $("#table-users").dataTable();
    </script>
    <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
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
                    const response = await fetch(`/account/security/role/add`, {
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
                        Notiflix.Notify.success('Success: Role created successfully.');
                        location.reload();
                    }
                } catch (error) {
                    Notiflix.Notify.failure('Error:',
                        'An error occurred while processing the request.');
                }
            });
        });
    </script>

    <script>
        const deletePermission = document.querySelectorAll('.role-delete');

        deletePermission.forEach(button => {
            button.addEventListener('click', function() {
                const cardId = button.dataset.cardId;

                Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                    'Batal',
                    function() {
                        fetch(`/account/security/role/delete/${cardId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                Notiflix.Notify.success("Data role berhasil dihapus.", {
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
@endpush
