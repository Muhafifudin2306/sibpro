@extends('layouts.admin.app')

@section('title_page', 'Pembayaran Tagihan')

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
                        <h1>{{ __('Pembayaran Tagihan') }}</h1>
                    </div>
                    @can('access-currentYear')
                        <div class="current__year d-md-flex d-block py-lg-0 pt-3 pb-1">
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
                        <h2 class="section-title">{{ __('Pembayaran Tagihan') }}</h2>
                        <p class="section-lead">
                            {{ __('Pilih item dan lakukan pembayaran') }}
                        </p>
                    </div>
                </div>

                {{-- <div class="row flex-row-reverse">
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header bg-warning">
                                <h4>{{ __('Petunjuk Pembayaran Tagihan') }}</h4>
                            </div>
                            <div class="card-body">
                                <p>1. Pilih item tagihan pada tabel <span class="font-weight-bold">"Daftar
                                        Tagihan Dipesan"</span> </p>
                                <p>2. Tabel <span class="font-weight-bold">"Estimasi Pembayaran"</span> akan menghitung
                                    total
                                    tagihan </p>
                                <p>3. Tekan tombol <span class="font-weight-bold">"Bayar Offline"</span> untuk pembayaran
                                    secara tunai ke TU Sekolah</p>
                                <p>4. Tekan tombol <span class="font-weight-bold">"Bayar Online"</span> untuk pembayaran
                                    secara digital melalui berbagai pilihan metode pembayaran</p>
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <button data-toggle="modal" data-target="#offlineModal"
                                                class="btn btn-primary w-100"> Bayar Offline
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
                                <h4>{{ __('Daftar Tagihan Dipesan') }}</h4>
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
                </div> --}}
                @if ($credit == '[]')
                    <div class="d-flex justify-content-center">
                        <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>

                        <dotlottie-player src="https://lottie.host/12fdb98b-48bb-439a-bdcb-4fa4d29594e5/oHVweoRcJ6.json"
                            background="transparent" speed="1" style="width: 300px; height: 300px;" loop
                            autoplay></dotlottie-player>
                    </div>
                    <p class="text-center font-weight-bold">Belum ada data pembayaran</p>
                @endif
                <div class="row">
                    @foreach ($credit as $item)
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4>Nomor Kwitansi : {{ $item->invoice_number }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="left-label">
                                            <p> Tanggal Transaksi : </p>
                                        </div>
                                        <div class="right-label">
                                            <h6 class="font-weight-light">{{ $item->updated_at->format('d M Y H:i') }} WIB
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="left-label">
                                            <p> Tanggal Tenggat Pembayaran : </p>
                                        </div>
                                        @if ($item->payment_type == 'Online')
                                            <div class="right-label">
                                                <h6 class="font-weight-light">
                                                    {{ $item->updated_at->addDay()->format('d M Y H:i') }} WIB</h6>
                                            </div>
                                        @elseif($item->payment_type == 'Offline')
                                            <div class="right-label">
                                                <h6 class="font-weight-light">-</h6>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="left-label">
                                            <p> Metode Pembayaran : </p>
                                        </div>
                                        <div class="right-label">
                                            <h6 class="font-weight-light">{{ $item->payment_type }}</h6>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="left-label">
                                            <p> Status Pembayaran : </p>
                                        </div>
                                        @if (!$time_now->greaterThan($item->updated_at->addDay()))
                                            <div class="right-label">
                                                <h6 class="font-weight-light">{{ $item->status }}</h6>
                                            </div>
                                        @elseif ($item->payment_type == 'Offline')
                                            <div class="right-label">
                                                <h6 class="font-weight-light">{{ $item->status }}</h6>
                                            </div>
                                        @else
                                            <div class="right-label">
                                                <h6 class="font-weight-light">Expired</h6>
                                            </div>
                                        @endif
                                    </div>
                                    <h6 class="py-2">Penghitungan Biaya</h6>
                                    <div class="d-flex justify-content-between">
                                        <div class="left-label ml-3">
                                            <p>Jumlah Pembayaran : </p>
                                        </div>
                                        <div class="right-label">
                                            <h6 class="font-weight-light" id="amount-{{ $item->invoice_number }}">
                                                Rp{{ number_format($item->total_price, 0, ',', '.') }}</h6>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="left-label ml-3">
                                            <p> Biaya Administrasi : </p>
                                        </div>
                                        <div class="right-label">
                                            @if ($item->payment_type == 'Online')
                                                <h6 class="font-weight-light" id="fee-{{ $item->invoice_number }}">
                                                    Rp{{ number_format(4500, 0, ',', '.') }}</h6>
                                            @elseif($item->payment_type == 'Offline')
                                                <h6 class="font-weight-light" id="fee-{{ $item->invoice_number }}">
                                                    Rp{{ number_format(0, 0, ',', '.') }}</h6>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <div class="left-label ml-3">
                                            <p> Total Pembayaran :
                                            </p>
                                        </div>
                                        <div class="right-label">
                                            <h6 class="font-weight-bold" id="total-{{ $item->invoice_number }}"></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-whitesmoke br">
                                    {{-- @if (!$time_now->greaterThan($item->updated_at->addDay())) --}}
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                @if ($item->payment_type == 'Online' && !$time_now->greaterThan($item->updated_at->addDay()))
                                                    <a href="{{ $item->checkout_link }}">
                                                        <button class="btn btn-primary w-100">Bayar Sekarang</button>
                                                    </a>
                                                @elseif($item->payment_type == 'Offline')
                                                    <button class="btn btn-primary w-100" data-toggle="modal"
                                                        data-target="#offlineModal{{ $item->invoice_number }}">Bayar
                                                        Sekarang</button>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <a href="{{ url('payment/cancel/' . $item->uuid) }}">
                                                    <button class="btn btn-danger w-100">Batalkan Transaksi</button>
                                                </a>
                                            </div>
                                        </div>
                                    {{-- @else --}}
                                        {{-- <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <a href="{{ url('payment/cancel/' . $item->uuid) }}">
                                                    <button class="btn btn-danger w-100">Batalkan Transaksi</button>
                                                </a>
                                            </div>
                                        </div> --}}
                                    {{-- @endif --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                {{ __('Development by Muhammad Afifudin') }}</a>
            </div>
        </footer>
    </div>
    @foreach ($credit as $item)
        <div class="modal fade" tabindex="-1" role="dialog" id="offlineModal{{ $item->invoice_number }}">
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
                            <p>1. Pergi menuju ke Ruang TU dan menemui petugas yang sedang bertugas melayani
                                pembayaran siswa
                            </p>
                            <p>2. Ajukan pembayaran untuk item tagihan dengan nomor kwitansi <span
                                    class="font-weight-bold">
                                    {{ $item->invoice_number }} </span>
                            </p>
                            <p>3. Lakukan pembayaran dengan nominal pembayaran <span class="font-weight-bold"
                                    id="total-pay-{{ $item->invoice_number }}"></span> </p>
                            <p>4. Pembayaran selesai dan cek laman <span class="font-weight-bold">Pembayaran
                                    Berhasil</span>
                                untuk melihat nota pembayaran</p>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-danger"
                            data-dismiss="modal">{{ __('Tutup Instruksi') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @push('scripts')
        @foreach ($credit as $item)
            <script>
                // Ambil nilai fee dan total
                var feeElement = document.getElementById('fee-{{ $item->invoice_number }}');
                var amountElement = document.getElementById('amount-{{ $item->invoice_number }}');
                var totalElement = document.getElementById('total-{{ $item->invoice_number }}');
                var totalPayElement = document.getElementById('total-pay-{{ $item->invoice_number }}');

                // Ambil nilai fee dari teks dalam elemen
                var fee = parseFloat(feeElement.textContent.replace('Rp', '').replace(',', ''));

                // Ambil nilai total dari teks dalam elemen
                var amount = parseFloat(amountElement.textContent.replace('Rp', '').replace(',', ''));

                // Tambahkan nilai fee ke dalam nilai total
                var newTotal = (fee + amount) * 1000;

                // Ubah teks dalam elemen total menjadi nilai total yang baru
                totalElement.textContent = 'Rp ' + newTotal.toLocaleString();
                totalPayElement.textContent = 'Rp ' + newTotal.toLocaleString();
            </script>
        @endforeach
        <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
    @endpush
@endsection
