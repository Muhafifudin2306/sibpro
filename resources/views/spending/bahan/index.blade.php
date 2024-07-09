@extends('layouts.admin.app')

@section('title_page', 'Belanja Bahan dan Alat List')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    @endpush

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin :students="$students"></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header d-flex justify-content-lg-between">
                    <h1>{{ __('Belanja Alat dan Bahan') }}</h1>
                    @can('access-changeYear')
                        <form id="updateYearForm">
                            @csrf
                            <div class="current__year d-flex py-lg-0 pt-3 pb-1">
                                <div class="year__active mr-2">
                                    <select class="form-control" name="year_name">
                                        @foreach ($years as $item)
                                            <option value="{{ $item->year_name }}"
                                                {{ $item->year_current == 'selected' ? 'selected' : '' }}>
                                                Tahun Ajaran: {{ $item->year_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="button-submit">
                                    <button type="button" onclick="updateYear()" class="btn btn-primary h-100">Simpan</button>
                                </div>
                            </div>
                        </form>
                    @endcan
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Data Belanja') }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih dan Tambah Data Belanja') }}
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
                        <h4>{{ __('Tabel Belanja') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-tagihan-vendor">
                                <thead>
                                    <tr>
                                        <th style="width:10px">{{ __('No') }}</th>
                                        <th>{{ __('Nomor Kwitansi') }}</th>
                                        <th>{{ __('Tanggal Pembelian') }}</th>
                                        <th>{{ __('Uraian') }}</th>
                                        <th>{{ __('Nominal Pembelian') }}</th>
                                        <th>{{ __('Bukti') }}</th>
                                        <th>{{ __('Aksi') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($bahans as $item)
                                        <tr>
                                            <td>
                                                {{ $no++ }}
                                            </td>
                                            <td>
                                                {{ $item->invoice_number }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item->spending_date)->format('F d, Y') }}
                                            </td>
                                            <td>
                                                {{ $item->spending_desc }}
                                            </td>
                                            <td>
                                                Rp{{ number_format($item->spending_price, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if (!$item->image_url || $item->image_url == null)
                                                    <span>No image provided.</span>
                                                @else
                                                    <a target="_blank" href="{{ asset($item->image_url) }}">Lihat
                                                        Bukti Pembayaran</a>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-start">
                                                    @can('access-classUpdate')
                                                        <div class="text-warning mx-2 cursor-pointer" data-toggle="modal"
                                                            data-target="#updateModal{{ $item->id }}">
                                                            <i class="fas fa-pen" title="Edit Nama Belanja"></i>
                                                        </div>
                                                    @endcan
                                                    @can('access-classDelete')
                                                        <div class="text-danger mx-2 cursor-pointer">
                                                            <i class="fas class-delete fa-trash-alt"
                                                                data-card-id="{{ $item->id }}" title="Delete Belanja"></i>
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
                        <h5 class="modal-title">Tambah Data Belanja</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="classForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="spending_date">Tanggal Belanja</label>
                                <input type="date" class="form-control" name="spending_date" id="spending_date" autofocus
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="spending_price">Uraian</label>
                                <textarea class="form-control" placeholder="Masukkan uraian pembelian" name="spending_desc" id="spending_desc"
                                    cols="30"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="spending_price">Nominal Belanja</label>
                                <input type="number" class="form-control" name="spending_price" id="spending_price"
                                    placeholder="500000" autofocus required>
                            </div>
                            <div class="form-group">
                                <label for="image_url">Bukti Belanja</label>
                                <input type="file" class="form-control" name="image_url" id="image_url"
                                    placeholder="Masukkan bukti belanja">
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
        @foreach ($bahans as $item)
            <div class="modal fade" tabindex="-1" role="dialog" id="updateModal{{ $item->id }}">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Data Belanja</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="update-form" data-action="{{ url('spending/bahan/update/' . $item->id) }}"
                            method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="spending_date">Nomor Kwitansi</label>
                                    <input type="text" class="form-control" name="invoice_number" id="invoice_number"
                                        value="{{ $item->invoice_number }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="spending_date">Tanggal Belanja</label>
                                    <input type="date" class="form-control" name="spending_date" id="spending_date"
                                        value="{{ $item->spending_date }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="spending_price">Uraian</label>
                                    <textarea class="form-control" placeholder="Masukkan uraian pembelian" name="spending_desc" id="spending_desc"
                                        cols="30"> {{ $item->spending_desc }} </textarea>
                                </div>
                                <div class="form-group">
                                    <label for="spending_price">Nominal Belanja</label>
                                    <input type="number" class="form-control" name="spending_price" id="spending_price"
                                        value="{{ round($item->spending_price) }}" autofocus required>
                                </div>
                                <div class="form-group">
                                    <label for="image_url">Bukti Belanja</label>
                                    <input type="file" class="form-control" name="image_url" id="image_url"
                                        placeholder="Masukkan bukti belanja">
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
            {{-- <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const classForm = document.getElementById('classForm');
                    classForm.addEventListener('submit', function(event) {
                        event.preventDefault();
                        const formData = new FormData(classForm);
                        const classData = {};
                        formData.forEach((value, key) => {
                            classData[key] = value;
                        });
                        fetch(`/spending/bahan/store`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    // 'Content-Type': 'application/json'
                                },
                                body: JSON.stringify(classData)
                            })
                            .then(response => response.json())
                            .then(data => {
                                Notiflix.Notify.success("Data Belanja berhasil dibuat!", {
                                    timeout: 3000
                                });
                                location.reload();
                            })
                            .catch(error => {
                                Notiflix.Notify.failure('Error:', error);
                            });
                    });
                });
            </script> --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const classForm = document.getElementById('classForm');
                    classForm.addEventListener('submit', function(event) {
                        event.preventDefault();
                        const formData = new FormData(classForm);

                        fetch(`/spending/bahan/store`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                Notiflix.Notify.success("Data Belanja berhasil dibuat!", {
                                    timeout: 3000
                                });
                                location.reload();
                            })
                            .catch(error => {
                                Notiflix.Notify.failure('Error:', error.message || error);
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
                                fetch(`/spending/bahan/delete/${cardId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        Notiflix.Notify.success("Data bahan berhasil dihapus!", {
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
                                    Notiflix.Notify.success("Data Belanja berhasil diperbarui!", {
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

        <script>
            function updateYear() {
                const form = document.getElementById('updateYearForm');
                const formData = new FormData(form);

                fetch('/current-year', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Terjadi kesalahan');
                        }
                        return response.json();
                    })
                    .then(data => {
                        Notiflix.Notify.success(data.message, {
                            timeout: 3000
                        });
                        location.reload();
                    })
                    .catch(error => {
                        Notiflix.Notify.failure('Error: Data tidak ditemukan!');
                    });
            }
        </script>

        @can('access-classList')
            <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
            <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
        @endcan
    @endpush
@endsection
