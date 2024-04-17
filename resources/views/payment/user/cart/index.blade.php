@extends('layouts.admin.app')

@section('title_page', 'Loket Pemesanan Tagihan')

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
                        <h1>{{ __('Loket Pemesanan') }}</h1>
                    </div>
                    @can('access-currentYear')
                        <div class="current__year d-md-flex d-block py-lg-0 pt-3 pb-1">
                            <div class="semester__active mr-2 mb-3">
                                <select class="form-control" name="year_semester" disabled>
                                    @foreach ($years as $item)
                                        <option value="{{ $item->year_semester }}"
                                            {{ $item->year_status == 'active' ? 'selected' : '' }}>
                                            Semester: {{ $item->year_semester }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="year__active mr-2 mb-3">
                                <select class="form-control" name="year_name" disabled>
                                    @foreach ($years as $item)
                                        <option value="{{ $item->year_name }}"
                                            {{ $item->year_status == 'active' ? 'selected' : '' }}>
                                            Tahun Ajaran: {{ $item->year_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endcan
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Loket Pemesanan') }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih item dan ketahui estimasi biayanya') }}
                        </p>
                    </div>
                </div>

                <div class="row flex-row-reverse">
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header bg-warning">
                                <h4>{{ __('Petunjuk Pemesanan Paket') }}</h4>
                            </div>
                            <div class="card-body">
                                <p>1. Pilih item tagihan pada tabel <span class="font-weight-bold">"Daftar
                                        Item Tagihan"</span> </p>
                                <p>2. Tabel <span class="font-weight-bold">"Estimasi Pembayaran"</span> akan menghitung
                                    total
                                    tagihan </p>
                                <p>3. Tekan tombol <span class="font-weight-bold">"Pesan Sekarang"</span> untuk melanjutkan
                                    proses pembayaran</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Estimasi Pembayaran') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="w-100">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Pembayaran</th>
                                                <th>Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody id="selectedItems"></tbody>
                                        <tbody>
                                            <tr>
                                                <td class="text-center font-weight-bold">Total</td>
                                                <td><span id="totalPrice">Rp0</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <button data-toggle="modal" data-target="#offlineModal"
                                                class="btn btn-primary w-100 submit"> Bayar Offline
                                            </button>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <button class="btn btn-success w-100 submit-online"> Bayar Online
                                            </button>
                                        </div>
                                    </div>
                                </div>
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
                                                                id="checkbox-{{ $item->id }}"
                                                                data-price="{{ $item->price }}"
                                                                data-type="{{ $item->type }}"
                                                                @if ($item->credit == null) data-label="{{ $item->attribute->attribute_name }}"
                                                            @elseif($item->credit != null)
                                                            data-label="{{ $item->credit->credit_name }}" @endif
                                                                onchange="updateTotalPrice()"
                                                                data-id="{{ $item->id }}">
                                                            <label for="checkbox-{{ $item->id }}"
                                                                class="custom-control-label">&nbsp;</label>
                                                        </div>
                                                    </td>
                                                    @if ($item->credit == null)
                                                        <td>
                                                            {{ $item->attribute->attribute_name }}
                                                            <p class="font-weight-bold d-block d-md-none">Harga :
                                                                Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                                        </td>
                                                    @elseif($item->credit != null)
                                                        <td>{{ $item->credit->credit_name }}

                                                            <p class="font-weight-bold d-block d-md-none">Harga :
                                                                Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                                        </td>
                                                    @endif
                                                    <td>{{ $item->type }}</td>
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

    @push('scripts')
        <script>
            document.querySelector('.submit-online').addEventListener('click', function() {
                Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin memesan paket ini ?', 'Ya', 'Tidak',
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

                        fetch('/api/payment/confirm', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    transactions: selectedIds
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
                                window.location.href =
                                    '/payment';
                            })
                            .catch(error => {
                                Notiflix.Notify.failure(error
                                    .message);
                            });
                    });
            });
        </script>

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
                Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin memesan paket ini ?', 'Ya', 'Tidak',
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

                        fetch('/cart/offline', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    transactions: selectedIds
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Gagal melakukan pemesanan data!');
                                }
                                return response.json();
                            })
                            .then(data => {
                                Notiflix.Notify.success(data.message);
                                window.location.href =
                                    '/payment';
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

                    labelItem += '<tr>' + '<td>' + label + '</td>' + '<td>' + 'Rp' +
                        priceItem +
                        '</td>' + '</tr>';
                });

                var selectedItemsTable = document.getElementById('selectedItems');
                selectedItemsTable.innerHTML = labelItem;

                var totalPriceSpan = document.getElementById('totalPrice');
                totalPriceSpan.textContent = 'Rp' + totalPrice
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

        <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
    @endpush
@endsection
