@extends('layouts.admin.app')

@section('title_page', 'All Transaction')

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
                <div class="section-header d-flex justify-content-lg-between">
                    <div class="title">
                        <h1>{{ __('Data Transaksi') }}</h1>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Data Transaksi') }}</h2>
                    </div>
                    <div class="action-content">
                        <button class="btn btn-primary generate">{{ __('+ Tambah Data') }}</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Pembayaran SPP dan Daftar Ulang') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <p class="font-weight-bold">Daftar Item</p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-md">
                                        <thead>
                                            <tr>
                                                <th>Pembayaran</th>
                                                <th>Tipe Pembayaran</th>
                                                <th>Nominal Pembayaran</th>
                                            </tr>
                                        </thead>

                                        <tbody id="selectedItems">
                                        </tbody>

                                        <tr>
                                            <td colspan="2" class="text-center font-weight-bold">Total</td>
                                            <td><span id="totalPrice">Rp0</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <p class="font-weight-bold">Pilih Metode Pembayaran</p>
                                <div class="mb-3">
                                    <button class="btn btn-warning w-100"> Bayar Langsung </button>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-info w-100"> Bayar Online </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Tabel Data Transaksi') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-spp">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                <div class="custom-checkbox custom-control">
                                                    <input type="checkbox" data-checkbox-role="dad"
                                                        data-checkboxes="mygroup" class="custom-control-input"
                                                        id="checkbox-all">
                                                    <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                                </div>
                                            </th>
                                            <th>{{ __('ID Transaksi') }}</th>
                                            <th>{{ __('Pembayaran') }}</th>
                                            <th>{{ __('Tipe') }}</th>
                                            <th>{{ __('Nama Siswa') }}</th>
                                            <th>{{ __('Nominal Pembayaran') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Tanggal') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($credit as $item)
                                            <tr>
                                                <td>
                                                    <div class="custom-checkbox custom-control">
                                                        <input type="checkbox" data-checkboxes="mygroup"
                                                            class="custom-control-input" id="checkbox-{{ $item->uuid }}"
                                                            data-price="{{ $item->price }}"
                                                            data-type="{{ $item->type }}"
                                                            @if ($item->credit == null) data-label="{{ $item->attribute->attribute_name }}"
                                                            @elseif($item->credit != null)
                                                            data-label="{{ $item->credit->credit_name }}" @endif
                                                            onchange="updateTotalPrice()">
                                                        <label for="checkbox-{{ $item->uuid }}"
                                                            class="custom-control-label">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>{{ $item->invoice_number }}</td>
                                                @if ($item->credit == null)
                                                    <td>{{ $item->attribute->attribute_name }}</td>
                                                @elseif($item->credit != null)
                                                    <td>{{ $item->credit->credit_name }}</td>
                                                @endif
                                                <td>{{ $item->type }}</td>
                                                <td>{{ $item->user->name }}</td>
                                                <td>
                                                    Rp{{ number_format($item->price, 0, ',', '.') }}
                                                </td>
                                                @if ($item->status == 'Paid')
                                                    <td>
                                                        <div class="badge badge-success">{{ $item->status }}</div>
                                                    </td>
                                                @else
                                                    <td>
                                                        <div class="badge badge-warning">{{ $item->status }}</div>
                                                    </td>
                                                @endif
                                                <td>{{ $item->updated_at->format('F d, Y') }}</td>
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
        <script>
            // Fungsi untuk memperbarui total harga
            function updateTotalPrice() {
                // Ambil semua checkbox yang dicentang, kecuali checkbox "Select All"
                var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked:not(#checkbox-all)');

                // Inisialisasi total harga
                var totalPrice = 0;
                var labelItem = '';

                // Loop melalui checkbox yang dicentang
                checkboxes.forEach(function(checkbox) {
                    // Ambil nilai harga dari atribut data-price
                    var price = parseFloat(checkbox.getAttribute('data-price'));
                    var label = checkbox.getAttribute('data-label');
                    var type = checkbox.getAttribute('data-type');
                    var priceItem = parseFloat(checkbox.getAttribute('data-price'));
                    // Tambahkan harga ke total harga
                    totalPrice += price;

                    labelItem += '<tr>' + '<td>' + label + '</td>' + '<td>' + type + '</td>' + '<td>' + 'Rp' +
                        priceItem +
                        '</td>' + '</tr>';
                });

                // Tampilkan total harga di luar loop foreach
                // Tampilkan daftar elemen yang dipilih di luar loop foreach
                var selectedItemsTable = document.getElementById('selectedItems');
                selectedItemsTable.innerHTML = labelItem;

                var totalPriceSpan = document.getElementById('totalPrice');
                totalPriceSpan.textContent = 'Rp' + totalPrice
                    .toLocaleString();
            }

            // Ambil checkbox "Select All"
            var checkboxAll = document.getElementById('checkbox-all');

            // Tambahkan event listener untuk checkbox "Select All"
            checkboxAll.addEventListener('change', function() {
                // Ambil semua checkbox dalam grup
                var checkboxes = document.querySelectorAll('input[type="checkbox"][data-checkboxes="mygroup"]');

                // Tentukan apakah checkbox "Select All" dicentang atau tidak
                var isChecked = checkboxAll.checked;

                // Setel status centang pada semua checkbox dalam grup
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = isChecked;
                });

                // Perbarui total harga
                updateTotalPrice();
            });

            // // Ambil semua checkbox dalam grup
            var checkboxes = document.querySelectorAll('input[type="checkbox"][data-checkboxes="mygroup"]');

            // // Tambahkan event listener untuk setiap checkbox dalam grup
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateTotalPrice);
            });
        </script>
        <script>
            // Simpan status checkbox di luar DOM
            var checkboxStatus = {};

            // Fungsi untuk memperbarui status checkbox saat checkbox diubah
            function updateCheckboxStatus(checkbox) {
                var id = checkbox.getAttribute('id');
                checkboxStatus[id] = checkbox.checked;
            }

            // Fungsi untuk menandai ulang checkbox sesuai dengan status yang disimpan
            function restoreCheckboxStatus() {
                var checkboxes = document.querySelectorAll('input[type="checkbox"][data-checkboxes="mygroup"]');
                checkboxes.forEach(function(checkbox) {
                    var id = checkbox.getAttribute('id');
                    if (checkboxStatus[id] === true) {
                        checkbox.checked = true;
                    } else {
                        checkbox.checked = false;
                    }
                });
            }

            // Ketika halaman dimuat, panggil fungsi untuk menandai ulang checkbox
            document.addEventListener('DOMContentLoaded', function() {
                restoreCheckboxStatus();
            });

            // Tambahkan event listener untuk setiap checkbox dalam grup
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    updateTotalPrice();
                    updateCheckboxStatus(checkbox);
                });
            });
        </script>
        <script>
            const generateButton = document.querySelectorAll('.generate');

            generateButton.forEach(button => {
                button.addEventListener('click', function() {
                    Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin Melanjutkan Proses ini?', 'Ya',
                        'Batal',
                        function() {
                            // Show loading animation only if user clicks "Ya"
                            Notiflix.Loading.standard('Proses sedang berlangsung, harap tunggu...');

                            fetch(`/income/credit/generate/`, {
                                    method: 'GET',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Notiflix.Notify.success("Data atribut SPP berhasil dibuat!", {
                                        timeout: 3000
                                    });

                                    // Remove loading animation after the process is complete
                                    Notiflix.Loading.remove();

                                    // Reload the page
                                    location.reload();
                                })
                                .catch(error => {
                                    Notiflix.Notify.failure('Error:', error);

                                    // Remove loading animation in case of error
                                    Notiflix.Loading.remove();
                                });
                        });
                });
            });
        </script>
        <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
    @endpush
@endsection
