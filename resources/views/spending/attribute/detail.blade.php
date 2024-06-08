@extends('layouts.admin.app')

@section('title_page')
    Realisasi Dana Atribut
@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin :students="$students"></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header d-flex justify-content-lg-between">
                    <div class="title">
                        <h1>Realisasi Dana Atribut</h1>
                    </div>
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
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Realisasi') }} {{ $attribute->attribute_name }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih dan Edit Data Realisasi Atribut') }}
                        </p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-success">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Debit</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <h5>
                                            Rp{{ number_format($sumDebit, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-danger">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Kredit</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <h5>
                                            Rp{{ number_format($sumSpending, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-primary">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Sisa Saldo</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <h5>
                                            Rp{{ number_format($sumDebit - $sumSpending, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="bg-warning">
                                    <div class="py-1"></div>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Hutang</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <h5>
                                            Rp{{ number_format($sumDebt, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="py-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="table-title">
                                <h4>{{ __('Tabel Kredit') }}</h4>
                            </div>
                            <div class="action-content">
                                <button class="btn btn-primary" data-toggle="modal"
                                    data-target="#creditModal">{{ __('+ Tambah Data') }}</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-student">
                                    <thead>
                                        <tr>
                                            <th>{{ __('No') }}</th>
                                            <th>{{ __('Tanggal') }}</th>
                                            <th>{{ __('Uraian') }}</th>
                                            <th>{{ __('Tipe Kredit') }}</th>
                                            <th>{{ __('Kredit') }}</th>
                                            <th>{{ __('Vendor/Pihak Terkait') }}</th>
                                            <th>{{ __('Bukti') }}</th>
                                            <th>{{ __('Aksi') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($spendings as $item)
                                            <tr>
                                                <td>
                                                    {{ $no++ }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($item->spending_date)->format('F d, Y') }}
                                                </td>

                                                <td>
                                                    {{ $item->spending_desc }}
                                                </td>
                                                <td>
                                                    {{ $item->spending_type == 1 ? 'Pembelian' : 'Non-Pembelian' }}
                                                </td>
                                                <td>
                                                    Rp{{ number_format($item->spending_price, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    {{ $item->vendor->vendor_name }}
                                                </td>
                                                <td>
                                                    <img src="{{ asset($item->image_url) }}" alt="" class="w-100">
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="text-warning mx-2 cursor-pointer" data-toggle="modal"
                                                            data-target="#creditEdit{{ $item->id }}">
                                                            <i class="fas fa-pen" title="Edit Credit"></i>
                                                        </div>
                                                        <div class="text-danger mx-2 cursor-pointer">
                                                            <i class="fas credit-delete fa-trash-alt"
                                                                data-card-id="{{ $item->id }}"
                                                                title="Delete Credit"></i>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="table-title">
                                <h4>{{ __('Tabel Hutang') }}</h4>
                            </div>
                            <div class="action-content">
                                <button class="btn btn-primary" data-toggle="modal"
                                    data-target="#debtModal">{{ __('+ Tambah Data') }}</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-debt">
                                    <thead>
                                        <tr>
                                            <th>{{ __('No') }}</th>
                                            <th>{{ __('Jatuh Tempo') }}</th>
                                            <th>{{ __('Uraian') }}</th>
                                            <th>{{ __('Jumlah Hutang') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Vendor/Pihak Terkait') }}</th>
                                            <th>{{ __('Bukti') }}</th>
                                            <th>{{ __('Aksi') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($debts as $item)
                                            <tr>
                                                <td>
                                                    {{ $no++ }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($item->due_date)->format('F d, Y') }}
                                                </td>

                                                <td>
                                                    {{ $item->description }}
                                                </td>
                                                <td>
                                                    Rp{{ number_format($item->debt_amount, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    @if ($item->is_paid == 1)
                                                        <div class="badge badge-success"> Lunas</div>
                                                    @elseif($item->is_paid == 0)
                                                        <div class="badge badge-warning">Belum Lunas</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $item->vendor->vendor_name }}
                                                </td>
                                                <td>
                                                    <img src="{{ asset($item->image_url) }}" alt="" class="w-100">
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="text-warning mx-2 cursor-pointer" data-toggle="modal"
                                                            data-target="#debtEdit{{ $item->id }}">
                                                            <i class="fas fa-pen" title="Edit Credit"></i>
                                                        </div>
                                                        <div class="text-danger mx-2 cursor-pointer">
                                                            <i class="fas debt-delete fa-trash-alt"
                                                                data-card-id="{{ $item->id }}"
                                                                title="Delete Credit"></i>
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
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                {{ __('Development by Muhammad Afifudin') }}
            </div>
        </footer>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="creditModal">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Tambah Data Kredit') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="creditForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="spending_date">{{ __('Tanggal Kredit') }}</label>
                            <input type="date" class="form-control" name="spending_date" id="spending_date" required>
                        </div>
                        <div class="form-group">
                            <label for="spending_desc">{{ __('Nama Pengeluaran') }}</label>
                            <input type="text" class="form-control" name="spending_desc" id="spending_desc"
                                placeholder="Masukkan nama pengeluaran" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Tipe Pengeluaran') }}</label>
                            <select class="form-control select2" name="spending_type" id="spending_type" required>
                                <option value="">{{ __('-- Pilih Tipe --') }}</option>
                                <option value="1">{{ __('Pembelian') }}</option>
                                <option value="0">{{ __('Non-Pembelian') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Nama Atribut') }}</label>
                            <select class="form-control select2" name="attribute_id" id="attribute_id" required>
                                <option value="{{ $attribute->id }}">{{ $attribute->attribute_name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Vendor/Pihak Terkait') }}</label>
                            <select class="form-control select2" name="vendor_id" id="vendor_id" required>
                                <option value="">{{ __('-- Pilih Vendor --') }}</option>
                                @foreach ($vendors as $item)
                                    <option value="{{ $item->id }}">{{ $item->vendor_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="spending_price">{{ __('Harga Pengeluaran') }}</label>
                            <input type="number" class="form-control" name="spending_price" id="spending_price"
                                placeholder="Masukkan besaran pengeluaran" required>
                        </div>
                        <div class="form-group">
                            <label for="image_url">{{ __('Bukti Pengeluaran') }}</label>
                            <input type="file" class="form-control" name="image_url" id="image_url"
                                accept="image/jpeg, image/png, image/jpg, image/gif, image/svg">
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

    <div class="modal fade" tabindex="-1" role="dialog" id="debtModal">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Tambah Data Hutang') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="debtForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="due_date">{{ __('Tanggal Jatuh Tempo') }}</label>
                            <input type="date" class="form-control" name="due_date" id="due_date">
                        </div>
                        <div class="form-group">
                            <label for="description">{{ __('Uraian Keperluan Hutang') }} </label>
                            <input type="text" class="form-control" name="description" id="description"
                                placeholder="Masukkan nama pengeluaran">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Status Hutang') }}</label>
                            <select class="form-control select2" name="is_paid">
                                <option>{{ __('-- Pilih Status --') }}</option>
                                <option value="1">{{ __('Lunas') }}</option>
                                <option value="0">{{ __('Belum Lunas') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Nama Atribut') }}</label>
                            <select class="form-control select2" name="attribute_id">
                                <option value="{{ $attribute->id }}">{{ $attribute->attribute_name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Vendor/Pihak Terkait') }}</label>
                            <select class="form-control select2" name="vendor_id">
                                <option>{{ __('-- Pilih Vendor --') }}</option>
                                @foreach ($vendors as $item)
                                    <option value="{{ $item->id }}">{{ $item->vendor_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="debt_amount">{{ __('Jumlah Hutang') }} </label>
                            <input type="number" class="form-control" name="debt_amount" id="debt_amount"
                                placeholder="Masukkan besaran pengeluaran" required>
                        </div>
                        <div class="form-group">
                            <label for="image_url">{{ __('Bukti Hutang') }}</label>
                            <input type="file" class="form-control" name="image_url" id="image_url"
                                placeholder="Masukkan bukti hutang">
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

    @foreach ($spendings as $item)
        <div class="modal fade" tabindex="-1" role="dialog" id="creditEdit{{ $item->id }}">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update Data Kredit') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="update-form" data-action="{{ url('/spending/attribute/update/' . $item->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="spending_date">{{ __('Tanggal Kredit') }}</label>
                                <input type="date" class="form-control" name="spending_date" id="spending_date"
                                    value="{{ $item->spending_date }}">
                            </div>
                            <div class="form-group">
                                <label for="spending_desc">{{ __('Nama Pengeluaran') }} </label>
                                <input type="text" class="form-control" name="spending_desc" id="spending_desc"
                                    value="{{ $item->spending_desc }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Tipe Pengeluaran') }}</label>
                                <select class="form-control select2" name="spending_type">
                                    <option value="1" {{ $item->spending_type == 1 ? 'selected' : '' }}>
                                        {{ __('Pembelian') }}</option>
                                    <option value="0" {{ $item->spending_type == 0 ? 'selected' : '' }}>
                                        {{ __('Non-Pembelian') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Nama Atribut') }}</label>
                                <select class="form-control select2" name="attribute_id">
                                    <option value="{{ $attribute->id }}">{{ $attribute->attribute_name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Penyedia Atribut') }}</label>
                                <select class="form-control select2" name="vendor_id" required="">
                                    <option selected disabled>{{ __('-- Pilih Penyedia --') }}</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}"
                                            {{ $vendor->id == $item->vendor_id ? 'selected' : '' }}>
                                            {{ $vendor->vendor_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="spending_price">{{ __('Harga Pengeluaran') }} </label>
                                <input type="number" class="form-control" name="spending_price" id="spending_price"
                                    value="{{ round($item->spending_price) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="image_url">{{ __('Bukti Pengeluaran') }}</label>
                                <input type="file" class="form-control" name="image_url" id="image_url"
                                    placeholder="Bukti pengeluaran">
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

    @foreach ($debts as $item)
        <div class="modal fade" tabindex="-1" role="dialog" id="debtEdit{{ $item->id }}">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update Data Hutang') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="update-form" data-action="{{ url('/spending/debt/update/' . $item->id) }} }}"
                        method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="due_date">{{ __('Tanggal Jatuh Tempo') }}</label>
                                <input type="date" class="form-control" value="{{ $item->due_date }}"
                                    name="due_date" id="due_date">
                            </div>
                            <div class="form-group">
                                <label for="description">{{ __('Uraian Keperluan Hutang') }} </label>
                                <input type="text" class="form-control" value="{{ $item->description }}"
                                    name="description" id="description" placeholder="Masukkan nama pengeluaran">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Tipe Pengeluaran') }}</label>
                                <select class="form-control select2" name="is_paid">
                                    <option value="1" {{ $item->is_paid == 1 ? 'selected' : '' }}>
                                        {{ __('Lunas') }}</option>
                                    <option value="0" {{ $item->is_paid == 0 ? 'selected' : '' }}>
                                        {{ __('Belum Lunas') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Nama Atribut') }}</label>
                                <select class="form-control select2" name="attribute_id">
                                    <option value="{{ $attribute->id }}">{{ $attribute->attribute_name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Penyedia Atribut') }}</label>
                                <select class="form-control select2" name="vendor_id" required="">
                                    <option selected disabled>{{ __('-- Pilih Penyedia --') }}</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}"
                                            {{ $vendor->id == $item->vendor_id ? 'selected' : '' }}>
                                            {{ $vendor->vendor_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="debt_amount">{{ __('Total Hutang') }} </label>
                                <input type="number" class="form-control" name="debt_amount" id="debt_amount"
                                    value="{{ round($item->debt_amount) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="image_url">{{ __('Bukti Hutang') }}</label>
                                <input type="file" class="form-control" name="image_url" id="image_url"
                                    placeholder="Bukti hutang">
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
@endsection

@push('scripts')
    <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>

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
                            Notiflix.Notify.success("Data berhasil diperbarui!", {
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
        const deleteCategory = document.querySelectorAll('.credit-delete');

        deleteCategory.forEach(button => {
            button.addEventListener('click', function() {
                const cardId = button.dataset.cardId;

                Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                    'Batal',
                    function() {
                        fetch(`/spending/attribute/delete/${cardId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                Notiflix.Notify.success("Data kredit berhasil dihapus!", {
                                    timeout: 3000
                                });
                                location.reload();
                            })
                            .catch(error => {
                                Notiflix.Notify.failure(
                                    'Error: Data kredit telah berelasi dengan tabel lainnya');
                            });
                    });
            });
        });
    </script>
    <script>
        const deleteDebt = document.querySelectorAll('.debt-delete');

        deleteDebt.forEach(button => {
            button.addEventListener('click', function() {
                const cardId = button.dataset.cardId;

                Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                    'Batal',
                    function() {
                        fetch(`/spending/debt/delete/${cardId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                Notiflix.Notify.success("Data hutang berhasil dihapus!", {
                                    timeout: 3000
                                });
                                location.reload();
                            })
                            .catch(error => {
                                Notiflix.Notify.failure(
                                    'Error: Data hutang telah berelasi dengan tabel lainnya');
                            });
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

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const creditForm = document.getElementById('creditForm');
            creditForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(creditForm);

                fetch(`/spending/attribute/add`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => {
                        console.log(response.json());
                        response.json()

                        return false;
                    })
                    .then(data => {
                        Notiflix.Notify.success("Data kredit berhasil dibuat!", {
                            timeout: 3000
                        });

                        // location.reload();
                    })
                    .catch(error => {
                            console.log(error);
                            Notiflix.Notify.failure('Error:', error);
                            return false;
                        }

                    );

            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
                    const creditForm = document.getElementById('debtForm');
                    creditForm.addEventListener('submit', async function(event) {
                            event.preventDefault();
                            const formData = new FormData(creditForm);
                            // const creditData = {};
                            // formData.forEach((value, key) => {
                            //     creditData[key] = value;
                            // });

                            const response = await fetch(`/spending/debt/add`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    // 'Content-Type': 'application/json'
                                },
                                // body: JSON.stringify(creditData)
                                body: formData
                            });
                            .then(response => {
                                    console.log(response.json());
                                    response.json()

                                    return false;
                                })
                                .then(data => {
                                    Notiflix.Notify.success("Data Debt berhasil dibuat!", {
                                        timeout: 3000
                                    });

                                    // location.reload();
                                })
                                .catch(error => {
                                    console.log(error);
                                    Notiflix.Notify.failure('Error:', error);
                                    return false;
                                });
                        );
                    });
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const creditForm = document.getElementById('creditForm');
            creditForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(creditForm);

                fetch(`/spending/attribute/add`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        Notiflix.Notify.success("Data kredit berhasil dibuat!", {
                            timeout: 3000
                        });
                        location.reload();
                    })
                    .catch(error => {
                        console.log(error);
                        Notiflix.Notify.failure('Error:', error);
                    });
            });

            const debtForm = document.getElementById('debtForm');
            debtForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                const formData = new FormData(debtForm);

                try {
                    const response = await fetch(`/spending/debt/add`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        Notiflix.Notify.success("Data Debt berhasil dibuat!", {
                            timeout: 3000
                        });
                        location.reload();
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                } catch (error) {
                    console.log(error);
                    Notiflix.Notify.failure('Error: ' + error.message);
                }
            });
        });
    </script>

    <script>
        $("#table-student").dataTable();
        $("#table-debt").dataTable();
    </script>
@endpush
