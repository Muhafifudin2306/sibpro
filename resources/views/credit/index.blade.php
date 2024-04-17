@extends('layouts.admin.app')

@section('title_page', 'Verifikasi Tagihan Siswa')

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
                    <div class="title">
                        <h1>{{ __('Verifikasi Tagihan Siswa') }}</h1>
                    </div>
                    @can('access-changeYear')
                        <form id="updateYearForm">
                            @csrf
                            <div class="current__year d-flex py-lg-0 pt-3 pb-1">
                                <div class="semester__active mr-2">
                                    <select class="form-control" name="year_semester">
                                        @foreach ($years as $item)
                                            <option value="{{ $item->year_semester }}"
                                                {{ $item->year_current == 'selected' ? 'selected' : '' }}>
                                                Semester: {{ $item->year_semester }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
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
                        <h2 class="section-title">{{ __('Verifikasi Tagihan Siswa') }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih item dan lakukan pembayaran') }}
                        </p>
                    </div>
                    @can('access-generatePayment')
                        <div class="action-content">
                            <button class="btn btn-primary generate">{{ __('+ Buat Data Tagihan') }}</button>
                        </div>
                    @endcan
                </div>

                <div class="row flex-row-reverse">
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header bg-warning">
                                <h4>{{ __('Petunjuk Verifikasi Tagihan Siswa') }}</h4>
                            </div>
                            <div class="card-body">
                                <p>1. Pilih item tagihan pada tabel <span class="font-weight-bold">"Daftar Item
                                        Tagihan"</span> </p>
                                <p>2. Untuk siswa yang telah melakukan pemesanan, maka item tagihan akan memiliki status
                                    <span class="font-weight-bold">"Pending"</span>
                                </p>
                                <p>2. Untuk siswa yang belum melakukan pemesanan, maka item tagihan akan memiliki status
                                    <span class="font-weight-bold">"Belum Lunas"</span>
                                </p>
                                <p>2. Tabel <span class="font-weight-bold">"Estimasi Pembayaran"</span> akan menghitung
                                    total
                                    tagihan </p>
                                <p>3. Keduanya dapat dilanjutkan proses pembayarannya dan tekan tombol <span
                                        class="font-weight-bold">"Konfirmasi Pembayaran"</span> ketika telah menerima uang
                                    tunai dari siswa
                                    maka </p>
                                <p>4. Tidak lupa memasukkan data <span class="font-weight-bold">"Petugas"</span> penerima
                                    transaksi saat itu</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Estimasi Pembayaran') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Pembayaran</th>
                                                <th>Tipe</th>
                                                <th>Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="selectedItems"></tbody>
                                        <tbody>
                                            <tr>
                                                <td colspan="2" class="text-center font-weight-bold">Total</td>
                                                <td><span id="totalPrice">Rp0</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <label for="signature">Petugas</label>
                                    <select name="petugas_id" id="petugas_id" required class="form-control">
                                        <option value="">Pilih Petugas</option>
                                        @foreach ($petugas as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button class="btn btn-primary w-100 submit"> Konfirmasi Pembayaran
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Daftar Item Tagihan') }}</h4>
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
                                                        <label for="checkbox-all"
                                                            class="custom-control-label">&nbsp;</label>
                                                    </div>
                                                </th>
                                                <th>{{ __('Pembayaran') }}</th>
                                                <th>{{ __('Tipe') }}</th>
                                                <th>{{ __('Nama Siswa') }}</th>
                                                <th>{{ __('Nominal') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($credit as $item)
                                                <tr>
                                                    <td>
                                                        <div class="custom-checkbox custom-control">
                                                            <input type="checkbox" data-checkboxes="mygroup"
                                                                class="custom-control-input"
                                                                id="checkbox-{{ $item->uuid }}"
                                                                data-price="{{ $item->price }}"
                                                                data-type="{{ $item->type }}"
                                                                @if ($item->credit == null) data-label="{{ $item->attribute->attribute_name }}"
                                                            @elseif($item->credit != null)
                                                            data-label="{{ $item->credit->credit_name }}" @endif
                                                                onchange="updateTotalPrice()"
                                                                data-id="{{ $item->id }}">
                                                            <label for="checkbox-{{ $item->uuid }}"
                                                                class="custom-control-label">&nbsp;</label>
                                                        </div>
                                                    </td>
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
                                                            <div class="badge badge-success">{{ $item->status }}
                                                            </div>
                                                        </td>
                                                    @elseif ($item->status == 'Unpaid')
                                                        <td>
                                                            <div class="badge badge-danger">Belum Lunas
                                                            </div>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <div class="badge badge-warning">{{ $item->status }}
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="offlineModal">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Instruksi Pembayaran Offline') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
                    <div class="d-flex justify-content-center mb-3">
                        <dotlottie-player src="https://lottie.host/e90dedc4-f416-4022-a3b2-c3c2c67728dc/vt2riNUNbu.json"
                            background="transparent" speed="1" style="width: 200px; height: 200px;" loop
                            autoplay></dotlottie-player>
                    </div>
                    <div class="py-3">
                        <p>1. Pergi menuju ke Ruang TU dan menemui petugas yang sedang bertugas melayani pembayaran siswa
                        </p>
                        <p>2. Ajukan pembayaran untuk item tagihan dengan rincian </p>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Pembayaran</th>
                                    <th>Tipe</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="selectedItems"></tbody>
                        </table>
                        <p>3. Lakukan pembayaran dengan nominal pembayaran <span class="font-weight-bold"
                                id="totalPricePay">Rp0</span></p>
                        <p>4. Pembayaran selesai dan cek laman <span class="font-weight-bold">Pembayaran Berhasil</span>
                            untuk mengunduh nota pembayaran</p>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger"
                        data-dismiss="modal">{{ __('Tutup Instruksi') }}</button>
                </div>
            </div>
        </div>
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
            document.querySelector('.submit').addEventListener('click', function() {
                Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin melakukan verifikasi pembayaran ?', 'Ya',
                    'Tidak',
                    function() {
                        var selectedIds = [];
                        document.querySelectorAll('input[type="checkbox"]:checked:not(#checkbox-all)').forEach(
                            function(checkbox) {
                                selectedIds.push(checkbox.getAttribute('data-id'));
                            });

                        if (selectedIds.length === 0) {
                            Notiflix.Notify.failure('Pilih setidaknya satu transaksi untuk pembayaran tagihan');
                            return;
                        }

                        // Dapatkan nilai petugas_id dari elemen select
                        var petugasId = document.getElementById('petugas_id').value;

                        if (petugasId == '') {
                            Notiflix.Notify.failure('Pilih Petugas Verifikator!');
                            return;
                        }

                        fetch('/payment/confirm', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    transactions: selectedIds,
                                    petugas_id: petugasId // Sisipkan nilai petugas_id ke dalam objek
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Gagal melakukan pembayaran online');
                                }
                                return response.json();
                            })
                            .then(data => {
                                Notiflix.Notify.success(data.message);
                                location.reload();
                            })
                            .catch(error => {
                                Notiflix.Notify.failure(error
                                    .message);
                            });
                    });
            });
        </script>

        <script>
            function updateTotalPrice() {
                var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked:not(#checkbox-all)');
                var totalPrice = 0;
                var labelItem = '';

                checkboxes.forEach(function(checkbox) {
                    var price = parseFloat(checkbox.getAttribute('data-price'));
                    var label = checkbox.getAttribute('data-label');
                    var type = checkbox.getAttribute('data-type');
                    var priceItem = parseFloat(checkbox.getAttribute('data-price'));
                    totalPrice += price;

                    labelItem += '<tr>' + '<td>' + label + '</td>' + '<td>' + type + '</td>' + '<td>' + 'Rp' +
                        priceItem +
                        '</td>' + '</tr>';
                });

                var selectedItemsTables = document.getElementsByClassName('selectedItems');
                for (var i = 0; i < selectedItemsTables.length; i++) {
                    selectedItemsTables[i].innerHTML = labelItem;
                }

                var totalPriceSpan = document.getElementById('totalPrice');
                totalPriceSpan.textContent = 'Rp' + totalPrice
                    .toLocaleString();

                var totalPricePay = document.getElementById('totalPricePay');
                totalPricePay.textContent = 'Rp' + totalPrice
                    .toLocaleString();
            }

            var checkboxAll = document.getElementById('checkbox-all');

            checkboxAll.addEventListener('change', function() {
                var checkboxes = document.querySelectorAll('input[type="checkbox"][data-checkboxes="mygroup"]');

                var isChecked = checkboxAll.checked;

                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = isChecked;
                });

                updateTotalPrice();
            });

            var checkboxes = document.querySelectorAll('input[type="checkbox"][data-checkboxes="mygroup"]');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateTotalPrice);
            });
        </script>
        <script>
            var checkboxStatus = {};

            function updateCheckboxStatus(checkbox) {
                var id = checkbox.getAttribute('id');
                checkboxStatus[id] = checkbox.checked;
            }

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

            document.addEventListener('DOMContentLoaded', function() {
                restoreCheckboxStatus();
            });

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

                            fetch(`/credit/generate/`, {
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
