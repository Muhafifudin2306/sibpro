@extends('layouts.admin.app')

@section('title_page', 'External Spending')

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
                    <h1>{{ __('Data Pengeluaran Operasional') }}</h1>
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
                        <h2 class="section-title">{{ __('Data Pengeluaran Operasional') }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih dan Tambah Data Pengeluaran Operasional') }}
                        </p>
                    </div>
                    <div class="action-content">
                        @can('access-classAdd')
                            <button class="btn btn-primary" data-toggle="modal"
                                data-target="#AddModal">{{ __('+ Tambah Data') }}</button>
                        @endcan
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Tabel Pengeluaran Operasional') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-tagihan-vendor">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">
                                            {{ __('No') }}
                                        </th>
                                        <th>{{ __('Tanggal') }}</th>
                                        <th>{{ __('Tipe Pengeluaran') }}</th>
                                        <th>{{ __('Keterangan') }}</th>
                                        <th>{{ __('Nominal') }}</th>
                                        <th>{{ __('Bukti') }}</th>
                                        <th>{{ __('Terakhir Diubah') }}</th>
                                        <th>{{ __('Aksi') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($externals as $item)
                                        <tr>
                                            <td>
                                                {{ $no++ }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($item->spending_date)->isoFormat('D MMMM YYYY') }}
                                            </td>
                                            <td>
                                                @if ($item->spending_type == 1)
                                                    Anggaran Pemeliharaan & perbaikan berkala
                                                @elseif($item->spending_type == 2)
                                                    Anggaran kesiswaan
                                                @elseif($item->spending_type == 3)
                                                    Anggaran Daya dan Jasa
                                                @elseif($item->spending_type == 4)
                                                    Anggaran belanja alat tulis
                                                @elseif($item->spending_type == 5)
                                                    Anggaran konsumsi
                                                @elseif($item->spending_type == 6)
                                                    Anggaran pengembangan Guru & Tendik
                                                @elseif($item->spending_type == 7)
                                                    Anggaran Transportasi & perjalanan dinas
                                                @elseif($item->spending_type == 8)
                                                    Lain-Lain
                                                @endif
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
                                                    <a target="_blank"
                                                        href="{{ asset('storage/petugas/' . $item->image_url) }}">Lihat
                                                        Bukti Pembayaran</a>
                                                @endif
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
                                                                data-card-id="{{ $item->id }}" title="Delete Kelas"></i>
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
    <div class="modal fade" tabindex="-1" role="dialog" id="AddModal">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Pengeluaran Operasional</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="classForm" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="spending_date">Tanggal</label>
                            <input type="date" class="form-control" name="spending_date" id="spending_date" required>
                        </div>
                        <div class="form-group">
                            <label for="spending_price">Nominal Dana</label>
                            <input type="number" class="form-control" name="spending_price" id="spending_price"
                                placeholder="100000" required>
                        </div>
                        <div class="form-group">
                            <label for="spending_type">Tipe Pengeluaran</label>
                            <select class="form-control" name="spending_type" id="spending_type" required>
                                <option value="" selected> -- Pilih Tipe Pengeluaran -- </option>
                                <option value="1">Anggaran Pemeliharaan & perbaikan berkala</option>
                                <option value="2">Anggaran kesiswaan</option>
                                <option value="3">Anggaran Daya dan Jasa</option>
                                <option value="4">Anggaran belanja alat tulis</option>
                                <option value="5">Anggaran konsumsi</option>
                                <option value="6">Anggaran pengembangan Guru & Tendik</option>
                                <option value="7">Anggaran Transportasi & perjalanan dinas</option>
                                <option value="8">Lain-Lain</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="spending_desc">Keterangan</label>
                            <textarea class="form-control" name="spending_desc" id="spending_desc" placeholder="Masukkan keterangan" cols="3"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image_url">Bukti</label>
                            <input type="file" class="form-control" name="image_url" id="image_url"
                                accept="image/*">
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
        @foreach ($externals as $item)
            <div class="modal fade" tabindex="-1" role="dialog" id="updateModal{{ $item->id }}">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Data Pengeluaran Operasional</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="update-form" enctype="multipart/form-data"
                            data-action="{{ url('/spending/operasional/update/' . $item->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="spending_date">Tanggal</label>
                                    <input value="{{ $item->spending_date }}" type="date" class="form-control"
                                        name="spending_date" required>
                                </div>
                                <div class="form-group">
                                    <label for="spending_price">Nominal Dana</label>
                                    <input value="{{ round($item->spending_price) }}" type="number" class="form-control"
                                        name="spending_price" id="spending_price" placeholder="100000" required>
                                </div>
                                <div class="form-group">
                                    <label for="spending_type">Tipe Pengeluaran</label>
                                    <select class="form-control" name="spending_type" id="spending_type" required>
                                        <option value="" selected> -- Pilih Tipe Pengeluaran -- </option>
                                        <option value="1" {{ $item->spending_type == 1 ? 'selected' : '' }}>Anggaran
                                            Pemeliharaan & perbaikan berkala</option>
                                        <option value="2" {{ $item->spending_type == 2 ? 'selected' : '' }}>Anggaran
                                            kesiswaan</option>
                                        <option value="3" {{ $item->spending_type == 3 ? 'selected' : '' }}>Anggaran Daya
                                            dan Jasa</option>
                                        <option value="4" {{ $item->spending_type == 4 ? 'selected' : '' }}>Anggaran
                                            belanja alat tulis</option>
                                        <option value="5" {{ $item->spending_type == 5 ? 'selected' : '' }}>Anggaran
                                            konsumsi</option>
                                        <option value="6" {{ $item->spending_type == 6 ? 'selected' : '' }}>Anggaran
                                            pengembangan Guru & Tendik</option>
                                        <option value="7" {{ $item->spending_type == 7 ? 'selected' : '' }}>Anggaran
                                            Transportasi & perjalanan dinas</option>
                                        <option value="8" {{ $item->spending_type == 8 ? 'selected' : '' }}>Lain-Lain
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="spending_desc">Keterangan</label>
                                    <textarea type="text" class="form-control" name="spending_desc" id="spending_desc"
                                        placeholder="Masukkan keterangan" cols="3" required>{{ $item->spending_desc }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="image_url">Bukti</label>
                                    <input type="file" class="form-control" name="image_url" id="image_url">
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
                    fetch(`/spending/operasional/store`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(classData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            Notiflix.Notify.success("Data Pengeluaran Operasional berhasil dibuat!", {
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

                    fetch(`/spending/operasional/store`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                // 'Content-Type': 'application/json'  // Jangan gunakan ini untuk FormData
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            Notiflix.Notify.success("Data Pengeluaran Operasional berhasil dibuat!", {
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
                            fetch(`/spending/operasional/delete/${cardId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Notiflix.Notify.success(
                                        "Data Pengeluaran Operasional berhasil dihapus!", {
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

        <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
    @endpush
@endsection
