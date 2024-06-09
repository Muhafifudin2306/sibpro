@extends('layouts.admin.app')

@section('title_page', 'Detail Pembayaran Siswa')

@section('content')
    @push('styles')
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endpush
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin :students="$students"></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header d-flex justify-content-lg-between">
                    <div class="title">
                        <h1>{{ __('Detail Pembayaran Siswa') }}</h1>
                    </div>
                </div>
                <div class="card">
                    <div class="invoice-header">
                        <div class="p-4">
                            <div class="row">
                                <div class="col-xl-2 col-md-3">
                                    <div class="d-flex justify-content-center">
                                        <img width="120" class="my-3"
                                            src="{{ asset('assets/img/logo/smk-du-logo.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-xl-10 col-md-9">
                                    <div class="my-1 text-center text-md-left">
                                        <h5 class="text-uppercase font-weight-light">Yayasan
                                            Pendidikan
                                            Islam Darul Musta'in
                                            Rejosari</h5>
                                        <h6 class="font-weight-light">Akta Notaris Tanggal 05 Mei 2011 No. 90 diperbarui
                                            Akta
                                            Notaris
                                            Tanggal 02 Agustus 2011 No .40</h6>
                                        <h4 class="text-uppercase font-weight-bold">SMK BP Darul Ulum</h4>
                                        <h6 class="font-weight-light">Alamat: Desa Rejosari Rt. 01 Rw 04 Kecamatan Grobogan,
                                            Kabupaten Grobogan</h6>
                                        <h6 class="font-weight-light">Provinsi Jawa Tengah Kode Pos. 58152 Tlp.
                                            (0292)4273035
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>

                    <div class="invoice-content">
                        <div class="px-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="font-weight-bold">{{ __('Nama Siswa') }}
                                        </label>
                                        <input type="text" class="form-control" value="{{ $credit->user->name }}"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">{{ __('NIS') }} </label>
                                        <input type="text" class="form-control" value="{{ $credit->user->nis }}"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="attribute_price">{{ __('Kelas') }} </label>
                                        <input type="text" class="form-control"
                                            value="{{ $credit->user->classes->class_name }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="attribute_price">{{ __('Nomor Kwitansi') }}
                                        </label>
                                        <input type="text" class="form-control" value="{{ $credit->invoice_number }}"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="attribute_price">{{ __('Tanggal Transaksi') }}
                                        </label>
                                        <input type="text" class="form-control"
                                            value="{{ $credit->updated_at->format('d F Y') }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="attribute_price">{{ __('Tahun Pelajaran') }}
                                        </label>
                                        <input type="text" class="form-control" value="{{ $credit->year->year_name }}"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="section-title">Ringkasan Pembayaran</div>
                                    <p class="section-lead">Semua item tagihan siswa</p>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('No') }}</th>
                                                    <th>{{ __('Pembayaran') }}</th>
                                                    <th>{{ __('Tipe') }}</th>
                                                    <th>{{ __('Nominal Pembayaran') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($credits as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        @if ($item->credit == null)
                                                            <td>{{ $item->attribute->attribute_name }}</td>
                                                        @elseif($item->credit != null)
                                                            <td>{{ $item->credit->credit_name }}</td>
                                                        @endif
                                                        <td>{{ $item->type }}</td>
                                                        <td>
                                                            Rp{{ number_format($item->price, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>Biaya Administrasi</td>
                                                    <td>Layanan Pembayaran Online</td>
                                                    <td>Rp5000</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="my-5">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold"
                                                for="attribute_price">{{ __('Total Transaksi') }}
                                            </label>
                                            <h3 class="font-weight-bold">
                                                Rp{{ number_format($totalPriceCredits, 0, ',', '.') }}
                                            </h3>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ url('payment') }}">
                                                <button class="btn btn-secodary"> Kembali</button>
                                            </a>
                                            <button class="btn btn-primary btn-icon icon-left mx-2" id="pay-button"> <i
                                                    class="fas fa-card"></i>
                                                Bayar Sekarang</button>
                                        </div>
                                    </div>
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
        <script type="text/javascript">
            document.getElementById('pay-button').onclick = function() {
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        console.log(result);
                    },
                    onPending: function(result) {
                        console.log(result);
                    },
                    onError: function(result) {
                        console.log(result);
                    },
                    onClose: function() {
                        console.log('Customer closed the popup without finishing the payment');
                    }
                });
            };
        </script>
    @endpush

@endsection
