@extends('layouts.admin.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.css') }}">

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Data Atribut') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active">Dashboard</div>
                        <div class="breadcrumb-item active">General Setting</div>
                        <div class="breadcrumb-item active">Atribut</div>
                        <div class="breadcrumb-item">Tambah</div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Tambah Kategori Atribut</h4>
                            <a href="{{ url('/setting/attribute') }}"> Close </a>
                        </div>
                        <div class="card-body pb-5">
                            <form action="{{ route('storeRelation') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select class="form-control select2" name="category_id">
                                        <option>-- Pilih Kategori --</option>
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Atribut Daftar Ulang</label>
                                    <select class="form-control select2" name="attribute_id[]" multiple="">
                                        @foreach ($attributes as $item)
                                            <option value="{{ $item->id }}">{{ $item->attribute_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Atribut SPP</label>
                                    <select class="form-control select2" name="credit_id[]" multiple="">
                                        @foreach ($credits as $item)
                                            <option value="{{ $item->id }}">{{ $item->credit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                Development by Muhammad Afifudin</a>
            </div>
        </footer>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
@endsection
