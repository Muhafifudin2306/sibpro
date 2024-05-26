<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Invoice {{ $credit->invoice_number }} | SIBPRO</title>
    <style>
        #logo {
            top: 10px;
            position: fixed;
            width: 20%;
            height: auto;
            right: auto;
            left: 30px;
        }

        #alamat {
            position: fixed;
            width: 80%;
            height: auto;
            right: 0;
        }

        #total {
            margin-top: 30px;
            width: 45%;
            height: auto;
            float: left;
        }

        #ttd {
            margin-top: 30px;
            width: 45%;
            height: auto;
            float: right;
            text-align: right;
        }

        h4 {
            line-height: 0px !important;
            letter-spacing: 0.5px !important;
            font-weight: 100 !important;
        }

        h3 {
            letter-spacing: 0.5px !important;
        }

        h5 {
            line-height: 0px !important;
            letter-spacing: 0.5px !important;
            font-weight: 100 !important;
        }

        .split-head {
            margin-top: 150px !important;
        }

        th,
        td {
            border: 1px solid black;
            padding: 10px;
        }

        .fw-bold {
            font-weight: bold !important;
        }

        .lunas-image {
            width: 100px;
            position: absolute;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="main-content">
        <section class="section">
            <div class="card">
                <div class="invoice-header">
                    <div class="p-4">
                        <div id="logo">
                            <img width="120"
                                src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('smk-du-logo.png'))) }}"
                                alt="">
                        </div>
                        <div id="alamat">
                            <div class="my-1 text-center text-md-left">
                                <h4 class="text-uppercase font-weight-light">Yayasan Pendidikan Islam Darul Musta'in
                                    Rejosari</h4>
                                <h5 class="font-weight-light">Akta Notaris Tanggal 05 Mei 2011 No. 90 diperbarui Akta
                                    Notaris Tanggal 02 Agustus 2011 No. 40</h5>
                                <h3 class="text-uppercase font-weight-bold">SMK BP Darul Ulum</h3>
                                <h5 class="font-weight-light">Alamat: Desa Rejosari Rt. 01 Rw 04 Kecamatan Grobogan,
                                    Kabupaten Grobogan</h5>
                                <h5 class="font-weight-light">Provinsi Jawa Tengah Kode Pos. 58152 Tlp. (0292)4273035
                                </h5>
                            </div>
                        </div>
                    </div>
                    <hr class="split-head">
                </div>
                <table width="100%" style="margin-top: 25px">
                    <tbody>
                        <tr>
                            <td width="15%"> <span class="fw-bold"> Nama Lengkap</span></td>
                            <td width="35%">{{ $credit->user->name }}</td>
                            <td width="15%"><span class="fw-bold"> Nomor Kwitansi</span></td>
                            <td width="35%">{{ $credit->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td width="15%"> <span class="fw-bold"> NIS</span></td>
                            <td width="35%">{{ $credit->user->nis }}</td>
                            <td width="15%"><span class="fw-bold"> Tanggal Transaksi</span></td>
                            <td width="35%">{{ $credit->updated_at->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td width="15%"> <span class="fw-bold"> Kelas</span></td>
                            <td width="35%">{{ $credit->user->classes->class_name }}</td>
                            <td width="15%"><span class="fw-bold"> Tahun Pelajaran</span></td>
                            <td width="35%">{{ $credit->year->year_name }}</td>
                        </tr>
                    </tbody>
                </table>
                <h4 style="padding-top: 15px;padding-bottom: 15px">Ringkasan Pembayaran</h4>
                <table width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pembayaran</th>
                            <th>Tipe Pembayaran</th>
                            <th>Nominal Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($credits as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
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
                    </tbody>
                </table>

                <div id="total">
                    <h4>Total Transaksi</h4>
                    <h1>Rp{{ number_format($totalPriceCredits, 0, ',', '.') }}</h1>
                    <img class="lunas-image"
                        src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('lunas-lurus.png'))) }}"
                        alt="Lunas">
                </div>
                <div id="ttd">
                    <h4>TTD Petugas Verifikator</h4>
                    <img width="150"
                        src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents('storage/petugas/' . $credit->petugas->signature)) }}"
                        alt="">
                    <h4>{{ $credit->petugas->name }}</h4>
                </div>
            </div>
        </section>
    </div>
</body>

</html>
