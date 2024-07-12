@extends('layouts.admin.app')

@section('title_page', 'Blangko Tagihan Siswa')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.css') }}">
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
                        <h1>{{ __('Blangko Tagihan Siswa') }}</h1>
                    </div>
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
                        <h2 class="section-title">{{ __('Blangko Tagihan Siswa') }}</h2>
                        <p class="section-lead">
                            {{ __('Buat dan edit blangko untuk siswa') }}
                        </p>
                    </div>
                    @can('access-generatePayment')
                        <div class="action-content d-flex">
                            <button class="btn btn-info mr-2 tagihan" data-toggle="modal"
                                data-target="#createBillModal">{{ __('+ Buat Data Tagihan') }}</button>
                            <button class="btn btn-primary generate mr-2">{{ __('+ Generate Data Tagihan') }}</button>
                        </div>
                    @endcan
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Estimasi Pembayaran') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="w-100">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>NIS</th>
                                                <th>Nama</th>
                                                <th>Pembayaran</th>
                                                <th>Tipe</th>
                                                <th>Tahun</th>
                                                <th>Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody id="selectedItems"></tbody>
                                        <tbody>
                                            <tr>
                                                <td colspan="5" class="text-center font-weight-bold">Total</td>
                                                <td><span id="totalPrice">Rp0</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mb-3 d-flex">
                                    <button class="btn btn-primary submit mx-1">
                                        <i class="fas fa-file mx-1"></i> Generate Invoice
                                    </button>
                                    <button class="btn btn-danger delete-bulk mx-1">
                                        <i class="fas fa-trash mx-1"></i> Delete Invoice
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
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
                                                <th>{{ __('NIS') }}</th>
                                                <th>{{ __('Nama Siswa') }}</th>
                                                <th>{{ __('Pembayaran') }}</th>
                                                <th>{{ __('Tipe') }}</th>
                                                <th>{{ __('Nominal') }}</th>
                                                <th>{{ __('Tahun') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Aksi') }}</th>
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
                                                                data-name="{{ $item->user->name }}"
                                                                data-year="{{ $item->year->year_name }}"
                                                                data-nis="{{ $item->user->nis }}"
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
                                                    <td>{{ $item->user->nis }}</td>
                                                    <td>{{ $item->user->name }}</td>
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
                                                    <td>{{ $item->year->year_name }}</td>
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
                                                    <td>
                                                        <div class="d-flex justify-content-start">
                                                            <a href="{{ route('editEnrollment', ['id' => $item->id]) }}">
                                                                <div class="text-warning mx-2 cursor-pointer">
                                                                    <i class="fas fa-pen" title="edit"></i>
                                                                </div>
                                                            </a>
                                                            <div class="text-danger mx-2 cursor-pointer">
                                                                <i class="fas payment-delete fa-trash-alt"
                                                                    data-card-id="{{ $item->id }}" title="delete"></i>
                                                            </div>
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
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                {{ __('Development by Muhammad Afifudin') }}</a>
            </div>
        </footer>
        @foreach ($credit as $item)
            <div class="modal fade" tabindex="-1" role="dialog" id="paymentModal{{ $item->id }}">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Update Data Item Tagihan') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="modal fade" role="dialog" id="createBillModal">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Buat Data Tagihan') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="createBillForm" action="{{ route('tagihan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ __('Pilih Siswa') }}</label>
                            <select class="form-control select2" name="user_id">
                                <option>{{ __('-- Pilih Siswa --') }}</option>
                                @foreach ($studentsList as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Tipe Pembayaran') }}</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" value="SPP"
                                    id="typeSPP" required>
                                <label class="form-check-label" for="typeSPP">{{ __('SPP') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" value="Daftar Ulang"
                                    id="typeDaftarUlang" required>
                                <label class="form-check-label" for="typeDaftarUlang">{{ __('Daftar Ulang') }}</label>
                            </div>
                        </div>

                        <div class="form-group" id="sppDropdown" style="display:none;">
                            <label for="sppItems">{{ __('Item SPP') }}</label>
                            <select class="form-control select2" id="sppItems" name="credit_id">
                                <option>{{ __('-- Pilih SPP --') }}</option>
                                @foreach ($creditList as $credit)
                                    <option value="{{ $credit->id }}">{{ $credit->credit_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="daftarUlangDropdown" style="display:none;">
                            <label for="daftarUlangItems">{{ __('Item Daftar Ulang') }}</label>
                            <select class="form-control select2" id="daftarUlangItems" name="attribute_id">
                                <option>{{ __('-- Pilih Item Daftar Ulang --') }}</option>
                                @foreach ($attributeList as $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->attribute_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Status Blangko') }}</label>
                            <select class="form-control" name="status" required>
                                <option value="">{{ __('-- Pilih Status --') }}</option>
                                <option value="Paid">{{ __('Lunas') }}</option>
                                <option value="Unpaid">{{ __('Belum Lunas') }}</option>
                            </select>
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
    @push('scripts')
        <script src="{{ asset('assets/modules/select2/dist/js/select2.full.js') }}"></script>
        <script>
            function formatRupiah(value) {
                if (!value) return '';
                return value.replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function formatInput(input) {
                const rawValue = input.value.replace(/\D/g, "");
                input.value = formatRupiah(rawValue);
                input.nextElementSibling.value = rawValue;
            }

            document.addEventListener("DOMContentLoaded", function() {
                const currencyInputs = document.querySelectorAll('.currency-format');
                currencyInputs.forEach(input => {
                    formatInput(input);

                    input.addEventListener('input', function() {
                        formatInput(input);
                    });
                });

                const form = document.getElementById('currencyForm');
                form.addEventListener('submit', function(event) {
                    currencyInputs.forEach(input => {
                        const rawValue = input.value.replace(/\D/g, "");
                        input.nextElementSibling.value = rawValue;
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const updateForms = document.querySelectorAll('.createBillForm');

                updateForms.forEach(form => {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault();
                        const formData = new FormData(form);

                        Notiflix.Loading.standard('Processing...');

                        fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: formData
                            })
                            .then(response => {
                                Notiflix.Loading.remove();

                                if (!response.ok) {
                                    return response.text().then(text => {
                                        throw new Error(text)
                                    });
                                }

                                return response.json();
                            })
                            .then(data => {
                                Notiflix.Loading.remove();

                                if (data.success) {
                                    Notiflix.Notify.success("Data tagihan berhasil dibuat!", {
                                        timeout: 3000
                                    });
                                    location.reload();
                                } else {
                                    Notiflix.Notify.failure(data.message ||
                                        'Terjadi kesalahan saat membuat data tagihan.');
                                }
                            })
                            .catch(error => {
                                Notiflix.Loading.remove();
                                Notiflix.Notify.failure('Error: ' + error.message);
                                console.error('Error', error);
                            });
                    });
                });

                document.querySelectorAll('input[name="type"]').forEach(function(element) {
                    element.addEventListener('change', function() {
                        if (this.value === 'SPP') {
                            document.getElementById('sppDropdown').style.display = 'block';
                            document.getElementById('daftarUlangDropdown').style.display = 'none';
                        } else if (this.value === 'Daftar Ulang') {
                            document.getElementById('sppDropdown').style.display = 'none';
                            document.getElementById('daftarUlangDropdown').style.display = 'block';
                        }
                    });
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
                                    Notiflix.Loading.remove();
                                    location.reload();
                                })
                                .catch(error => {
                                    Notiflix.Notify.failure('Error:', error);
                                    Notiflix.Loading.remove();
                                });
                        });
                });
            });
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

                        Notiflix.Loading.standard('Please wait...');

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
                                    '/credit';
                            })
                            .catch(error => {
                                Notiflix.Notify.failure(error
                                    .message);
                            });
                    });
            });
        </script>

        <script>
            document.querySelector('.delete-bulk').addEventListener('click', function() {
                Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus paket ini ?', 'Ya', 'Tidak',
                    function() {
                        var selectedIds = [];
                        document.querySelectorAll('input[type="checkbox"]:checked:not(#checkbox-all)').forEach(
                            function(checkbox) {
                                selectedIds.push(checkbox.getAttribute('data-id'));
                            });

                        if (selectedIds.length === 0) {
                            Notiflix.Notify.failure('Pilih setidaknya satu transaksi untuk dihapus!');
                            return;
                        }

                        Notiflix.Loading.standard('Please wait...');

                        fetch('/cart/delete', {
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
                                    throw new Error('Gagal melakukan penghapusan data!');
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
                    var name = checkbox.getAttribute('data-name');
                    var nis = checkbox.getAttribute('data-nis');
                    var type = checkbox.getAttribute('data-type');
                    var year = checkbox.getAttribute('data-year');
                    var priceItem = parseFloat(checkbox.getAttribute('data-price'));
                    totalPrice += price;

                    labelItem += '<tr>' + '<td>' + nis + '</td>' + '<td>' + name + '</td>' + '<td>' + type + '</td>' +
                        '<td>' + label + '</td>' + '<td>' + year + '</td>' +
                        '<td>' + 'Rp' + priceItem + '</td>' + '</tr>';
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

        <script>
            const deleteAttribute = document.querySelectorAll('.payment-delete');

            deleteAttribute.forEach(button => {
                button.addEventListener('click', function() {
                    const cardId = button.dataset.cardId;

                    Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                        'Batal',
                        function() {
                            fetch(`/enrollment/delete/${cardId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Notiflix.Notify.success(
                                        "Data atribut Tagihan Siswa berhasil dihapus!", {
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
