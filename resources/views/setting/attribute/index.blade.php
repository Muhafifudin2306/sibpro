@extends('layouts.admin.app')

@section('title_page', 'Package List')

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
                <div class="section-header">
                    <h1>{{ __('Paket') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('General Setting') }}</div>
                        <div class="breadcrumb-item active">{{ __('Paket') }}</div>
                    </div>
                </div>
                @can('access-packageList')
                    <div class="d-flex justify-content-between align-items-center pb-3">
                        <div class="title-content">
                            <h2 class="section-title">{{ __('Data Paket') }}</h2>
                            <p class="section-lead">
                                {{ __('Pilih dan Tambah Data Paket') }}
                            </p>
                        </div>
                        <div class="action-content">
                            <a href="{{ url('/setting/packages/download/excel') }}">
                                <button class="btn btn-success mr-2" data-toggle="modal" data-target="#relationModal">
                                    <i class="fas fa-file-archive mx-2"></i>
                                    {{ __('Download Data') }}</button>
                            </a>
                            @can('access-packageAdd')
                                <a href="{{ url('/setting/packages/add') }}">
                                    <button class="btn btn-primary" data-toggle="modal"
                                        data-target="#relationModal">{{ __('+ Tambah Data') }}</button>
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Tabel Paket') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-relation">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">
                                                {{ __('No') }}
                                            </th>
                                            <th>{{ __('Nama Paket') }}</th>
                                            <th>{{ __('Atribut Daftar Ulang') }}</th>
                                            <th>{{ __('Atribut SPP') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($categoriesRelation as $item)
                                            <tr>
                                                <td>
                                                    {{ $no++ }}
                                                </td>
                                                <td>
                                                    {{ $item->category_name }}
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-wrap">
                                                        @if ($item->attributes->count() > 0)
                                                            <div class="mt-2 text-primary font-weight-bold">
                                                                {{ $item->attributes->count() . ' ' . 'Atribut' }}
                                                            </div>
                                                        @else
                                                            <div class="mt-2">
                                                                Tidak ada Atribut
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-wrap">
                                                        @if ($item->credits->count() > 0)
                                                            <div class="mt-2 text-primary font-weight-bold">
                                                                {{ $item->credits->count() . ' ' . 'Atribut' }}
                                                            </div>
                                                        @else
                                                            <div class="mt-2">
                                                                Tidak ada Atribut
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        @can('access-packageEdit')
                                                            <a href="{{ url('setting/packages/edit/' . $item->id) }}">
                                                                <div class="text-warning mx-2 cursor-pointer">
                                                                    <i class="fas fa-pen" title="Edit"></i>
                                                                </div>
                                                            </a>
                                                        @endcan
                                                        @can('access-packageDelete')
                                                            <div class="text-danger mx-2 cursor-pointer">
                                                                <i class="fas package-delete fa-trash-alt"
                                                                    data-card-id="{{ $item->id }}" title="Delete"></i>
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
                @endcan

                @can('access-attributeList')
                    <div class="d-flex justify-content-between align-items-center pb-3">
                        <div class="title-content">
                            <h2 class="section-title">{{ __('Data Atribut Daftar Ulang') }}</h2>
                            <p class="section-lead">
                                {{ __('Pilih dan Tambah Data Atribut Daftar Ulang') }}
                            </p>
                        </div>
                        @can('access-attributeAdd')
                            <div class="action-content">
                                <button class="btn btn-primary" data-toggle="modal"
                                    data-target="#attributeModal">{{ __('+ Tambah Data') }}</button>
                            </div>
                        @endcan
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Tabel Atribut Daftar Ulang') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-attribute">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">
                                                {{ __('No') }}
                                            </th>
                                            <th>{{ __('Nama Atribut') }}</th>
                                            <th>{{ __('Harga Atribut') }}</th>
                                            <th>{{ __('Tipe Atribut') }}</th>
                                            <th>{{ __('Penyedia Atribut') }}</th>
                                            <th>{{ __('Diubah pada') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($attributes as $item)
                                            <tr>
                                                <td>
                                                    {{ $no++ }}
                                                </td>
                                                <td>
                                                    {{ $item->attribute_name }}
                                                </td>
                                                <td>
                                                    Rp{{ number_format($item->attribute_price, 0, ',', '.') }}
                                                </td>
                                                <td>{{ $item->attribute_type == 1 ? 'Pembelian' : 'Non-Pembelian' }}</td>
                                                <td>
                                                    {{ $item->vendors->vendor_name }}
                                                </td>
                                                <td>
                                                    {{ $item->updated_at->format('d F Y') }}
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        @can('access-attributeUpdate')
                                                            <div class="text-warning mx-2 cursor-pointer" data-toggle="modal"
                                                                data-target="#exampleModal{{ $item->slug }}">
                                                                <i class="fas fa-pen" title="Edit Atribut"></i>
                                                            </div>
                                                        @endcan
                                                        @can('access-attributeDelete')
                                                            <div class="text-danger mx-2 cursor-pointer">
                                                                <i class="fas attribute-delete fa-trash-alt"
                                                                    data-card-id="{{ $item->id }}" title="delete"></i>
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
                @endcan

                @can('access-creditList')
                    <div class="d-flex justify-content-between align-items-center pb-3">
                        <div class="title-content">
                            <h2 class="section-title">{{ __('Data Atribut SPP') }}</h2>
                            <p class="section-lead">
                                {{ __('Pilih dan Tambah Data Atribut SPP') }}
                            </p>
                        </div>
                        @can('access-creditAdd')
                            <div class="action-content">
                                <button class="btn btn-primary" data-toggle="modal"
                                    data-target="#creditModal">{{ __('+ Tambah Data') }}</button>
                            </div>
                        @endcan
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Tabel Atribut SPP') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-credit">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">
                                                {{ __('No') }}
                                            </th>
                                            <th>{{ __('Nama Atribut') }}</th>
                                            <th>{{ __('Kelas') }}</th>
                                            <th>{{ __('Harga Atribut') }}</th>
                                            <th>{{ __('Diubah pada') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($credits as $credit)
                                            <tr>
                                                <td>
                                                    {{ $no++ }}
                                                </td>
                                                <td>
                                                    {{ $credit->credit_name }}
                                                </td>
                                                <td>
                                                    {{ 'Kelas' . ' ' . $credit->semester }}
                                                </td>
                                                <td>
                                                    Rp{{ number_format($credit->credit_price, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    {{ $credit->updated_at->format('d F Y') }}
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        @can('access-creditUpdate')
                                                            <div class="text-warning mx-2 cursor-pointer" data-toggle="modal"
                                                                data-target="#creditEdit{{ $credit->id }}">
                                                                <i class="fas fa-pen" title="Edit Credit"></i>
                                                            </div>
                                                        @endcan
                                                        @can('access-creditDelete')
                                                            <div class="text-danger mx-2 cursor-pointer">
                                                                <i class="fas credit-delete fa-trash-alt"
                                                                    data-card-id="{{ $credit->id }}" title="Delete Credit"></i>
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
                @endcan

                @can('access-categoryList')
                    <div class="d-flex justify-content-between align-items-center pb-3">
                        <div class="title-content">
                            <h2 class="section-title">{{ __('Data Kategori') }}</h2>
                            <p class="section-lead">
                                {{ __('Pilih dan Tambah Data Kategori') }}
                            </p>
                        </div>
                        @can('access-categoryAdd')
                            <div class="action-content">
                                <button class="btn btn-primary" data-toggle="modal"
                                    data-target="#categoryModal">{{ __('+ Tambah Data') }}</button>
                            </div>
                        @endcan
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Tabel Kategori') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-category">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">
                                                {{ __('No') }}
                                            </th>
                                            <th>{{ __('Nama Kategori') }}</th>
                                            <th>{{ __('Diubah pada') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($categories as $item)
                                            <tr>
                                                <td>
                                                    {{ $no++ }}
                                                </td>
                                                <td>
                                                    {{ $item->category_name }}
                                                </td>
                                                <td>
                                                    {{ $item->updated_at->format('d F Y') }}
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        @can('access-categoryUpdate')
                                                            <div class="text-warning mx-2 cursor-pointer" data-toggle="modal"
                                                                data-target="#categoryModal{{ $item->slug }}">
                                                                <i class="fas fa-pen" title="Edit Category"></i>
                                                            </div>
                                                        @endcan
                                                        @can('access-categoryDelete')
                                                            <div class="text-danger mx-2 cursor-pointer">
                                                                <i class="fas category-delete fa-trash-alt"
                                                                    data-card-id="{{ $item->id }}" title="Delete"></i>
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
                @endcan
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                {{ __('Development by Muhammad Afifudin') }}</a>
            </div>
        </footer>
    </div>

    @can('access-attributeAdd')
        <div class="modal fade" tabindex="-1" role="dialog" id="attributeModal">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Tambah Data Atribut Daftar Ulang') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="attributeForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="attribute_name">{{ __('Nama Atribut') }}</label>
                                <input type="text" class="form-control" name="attribute_name" id="attribute_name"
                                    placeholder="Topi/Dasi/Seragam" autofocus required="">
                            </div>
                            <div class="form-group">
                                <label for="attribute_price">{{ __('Harga (Tulis : 100000)') }} </label>
                                <input type="number" class="form-control" name="attribute_price" id="attribute_price"
                                    placeholder="100000" required="">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Tipe Atribut') }}</label>
                                <select class="form-control select2" name="attribute_type" required="">
                                    <option>{{ __('-- Pilih Tipe --') }}</option>
                                    <option value="1">{{ __('Pembelian') }}</option>
                                    <option value="0">{{ __('Non-Pembelian') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Penyedia Atribut') }}</label>
                                <select class="form-control select2" name="vendor_id" required="">
                                    <option selected disabled>{{ __('-- Pilih Penyedia --') }}</option>
                                    @foreach ($vendors as $item)
                                        <option value="{{ $item->id }}">{{ $item->vendor_name }}</option>
                                    @endforeach

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
    @endcan

    @can('access-creditAdd')
        <div class="modal fade" tabindex="-1" role="dialog" id="creditModal">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Tambah Data Atribut SPP') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="creditForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="credit_name">{{ __('Nama Atribut') }}</label>
                                <input type="text" class="form-control" name="credit_name" id="credit_name"
                                    placeholder="SPP Juni" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="credit_price">{{ __('Nominal SPP') }} </label>
                                <input type="number" class="form-control currency-format" name="credit_price"
                                    id="credit_price" placeholder="80000">
                                <input type="hidden" name="credit_price" class="currency-raw">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Tipe Kelas') }}</label>
                                <select class="form-control select2" name="semester" required="">
                                    <option>{{ __('-- Pilih Jenjang Kelas --') }}</option>
                                    <option value="X">{{ __('Kelas X') }}</option>
                                    <option value="XI">{{ __('Kelas XI') }}</option>
                                    <option value="XII">{{ __('Kelas XII') }}</option>
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
    @endcan

    @can('access-categoryAdd')
        <div class="modal fade" tabindex="-1" role="dialog" id="categoryModal">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Tambah Data Kategori') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="categoryForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="category_name">{{ __('Nama Kategori') }}</label>
                                <input type="text" class="form-control" name="category_name" id="category_name"
                                    placeholder="Reguler" autofocus="" required="">
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
    @endcan

    @can('access-attributeUpdate')
        @foreach ($attributes as $item)
            <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal{{ $item->slug }}">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Update Data Attribute Daftar Ulang') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="update-form" data-action="{{ url('/setting/attribute/update/' . $item->id) }} }}"
                            method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="attribute_name">{{ __('Nama Atribut') }}</label>
                                    <input type="text" class="form-control" name="attribute_name" id="attribute_name"
                                        value="{{ $item->attribute_name }}" required="">
                                </div>
                                <div class="form-group">
                                    <label for="attribute_price">{{ __('Harga (Tulis : 100000)') }} </label>
                                    <input type="number" class="form-control" name="attribute_price" id="attribute_price"
                                        value="{{ round($item->attribute_price) }}" autofocus required="">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Tipe Kategori') }}</label>
                                    <select class="form-control select2" name="attribute_type" required="">
                                        <option value="1" {{ $item->attribute_type == 1 ? 'selected' : '' }}>
                                            {{ __('Pembelian') }}</option>
                                        <option value="0" {{ $item->attribute_type == 0 ? 'selected' : '' }}>
                                            {{ __('Non-Pembelian') }}</option>
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

    @can('access-creditUpdate')
        @foreach ($credits as $item)
            <div class="modal fade" tabindex="-1" role="dialog" id="creditEdit{{ $item->id }}">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Update Data Attribute SPP') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="update-form" data-action="{{ url('/setting/credit/update/' . $item->id) }}"
                            method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="credit_name">{{ __('Nama Atribut') }}</label>
                                    <input type="text" class="form-control" name="credit_name" id="credit_name"
                                        value="{{ $item->credit_name }}">
                                </div>
                                <div class="form-group">
                                    <label for="credit_price">{{ __('Nominal SPP') }}</label>
                                    <input type="number" class="form-control currency-format" id="credit_price"
                                        value="{{ round($item->credit_price) }}" autofocus>
                                    <input type="hidden" name="credit_price" class="currency-raw">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Tipe Kelas') }}</label>
                                    <select class="form-control select2" name="semester" required="">
                                        <option>{{ __('-- Pilih Jenjang Kelas --') }}</option>
                                        <option value="X" {{ $item->semester == 'X' ? 'selected' : '' }}>
                                            {{ __('Kelas X') }}</option>
                                        <option value="XI" {{ $item->semester == 'XI' ? 'selected' : '' }}>
                                            {{ __('Kelas XI') }}</option>
                                        <option value="XII" {{ $item->semester == 'XII' ? 'selected' : '' }}>
                                            {{ __('Kelas XII') }}</option>
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
        @endforeach
    @endcan

    @can('access-categoryUpdate')
        @foreach ($categories as $item)
            <div class="modal fade" tabindex="-1" role="dialog" id="categoryModal{{ $item->slug }}">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Update Data Kategori') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="update-form" data-action="{{ url('/setting/category/update/' . $item->id) }} }}"
                            method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="category_name">{{ __('Nama Kategori') }}</label>
                                    <input type="text" class="form-control" name="category_name" id="category_name"
                                        value="{{ $item->category_name }}" autofocus="" required="">
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

    @can('access-attributeAdd')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const attributeForm = document.getElementById('attributeForm');
                attributeForm.addEventListener('submit', async function(event) {
                    event.preventDefault();
                    const formData = new FormData(attributeForm);
                    const attributeData = {};
                    formData.forEach((value, key) => {
                        attributeData[key] = value;
                    });

                    try {
                        const response = await fetch(`/setting/attribute/add`, {
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
                            Notiflix.Notify.success('Data atribut Daftar Ulang berhasil dibuat!');
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

    @can('access-attributeDelete')
        <script>
            const deleteAttribute = document.querySelectorAll('.attribute-delete');

            deleteAttribute.forEach(button => {
                button.addEventListener('click', function() {
                    const cardId = button.dataset.cardId;

                    Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                        'Batal',
                        function() {
                            fetch(`/setting/attribute/delete/${cardId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Notiflix.Notify.success(
                                        "Data atribut Daftar Ulang berhasil dihapus!", {
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

    @can('access-categoryAdd')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const categoryForm = document.getElementById('categoryForm');
                categoryForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const formData = new FormData(categoryForm);
                    const yearData = {};
                    formData.forEach((value, key) => {
                        yearData[key] = value;
                    });

                    fetch(`/setting/category/add`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(yearData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            Notiflix.Notify.success("Data kategori berhasil dibuat!", {
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
    @endcan

    @can('access-packageDelete')
        <script>
            const deletePackage = document.querySelectorAll('.package-delete');

            deletePackage.forEach(button => {
                button.addEventListener('click', function() {
                    const cardId = button.dataset.cardId;

                    Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                        'Batal',
                        function() {
                            fetch(`/setting/packages/delete/${cardId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Notiflix.Notify.success("Data paket berhasil dihapus!", {
                                        timeout: 3000
                                    });
                                    location.href = "{{ url('setting/packages') }}";
                                })
                                .catch(error => {
                                    Notiflix.Notify.failure('Error:', error);
                                });
                        });
                });
            });
        </script>
    @endcan

    @can('access-categoryDelete')
        <script>
            const deleteCategory = document.querySelectorAll('.category-delete');

            deleteCategory.forEach(button => {
                button.addEventListener('click', function() {
                    const cardId = button.dataset.cardId;

                    Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                        'Batal',
                        function() {
                            fetch(`/setting/category/delete/${cardId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Notiflix.Notify.success("Data kategori berhasil dihapus!", {
                                        timeout: 3000
                                    });
                                    location.reload();
                                })
                                .catch(error => {
                                    Notiflix.Notify.failure(
                                        'Error: Data kategori telah berelasi dengan tabel lainnya');
                                });
                        });
                });
            });
        </script>
    @endcan

    @can('access-creditAdd')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const creditForm = document.getElementById('creditForm');
                creditForm.addEventListener('submit', async function(event) {
                    event.preventDefault();
                    const formData = new FormData(creditForm);
                    const creditData = {};
                    formData.forEach((value, key) => {
                        creditData[key] = value;
                    });

                    try {
                        const response = await fetch(`/setting/credit/add`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(creditData)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            const errorMessages = Object.values(errorData.errors).join('\n');
                            Notiflix.Notify.failure(
                                'Field tidak boleh kosong atau nama sejenis telah digunakan');
                        } else {
                            Notiflix.Notify.success('Data atribut SPP berhasil dibuat!');
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

    @can('access-creditDelete')
        <script>
            const deleteCredit = document.querySelectorAll('.credit-delete');

            deleteCredit.forEach(button => {
                button.addEventListener('click', function() {
                    const cardId = button.dataset.cardId;

                    Notiflix.Confirm.show('Konfirmasi', 'Apakah Anda yakin ingin menghapus data ini?', 'Ya',
                        'Batal',
                        function() {
                            fetch(`/setting/credit/delete/${cardId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Notiflix.Notify.success("Data atribut SPP berhasil dihapus!", {
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


    <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>

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
        $("#table-relation").dataTable();
        $("#table-category").dataTable();
        $("#table-credit").dataTable();
        $("#table-attribute").dataTable();
    </script>
@endpush
