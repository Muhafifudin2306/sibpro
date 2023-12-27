@extends('layouts.admin.app')

@section('title_page')
    Billing Student
@endsection

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    @endpush

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Pembayaran Daftar Ulang') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item active">{{ __('Pemasukan') }}</div>
                        <div class="breadcrumb-item">{{ __('Daftar Ulang') }}</div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Data Pembayaran') }} </h2>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Pembayaran Daftar Ulang') }}</h4>
                        </div>
                        <div class="card-body">
                            <form id="checklistForm" method="post" action="{{ route('processMultiplePayments') }}">
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-tagihan-vendor">
                                        <thead>
                                            <tr>
                                                <th>{{ __('No') }}</th>
                                                <th>{{ __('Nama Siswa') }}</th>
                                                @foreach ($student as $item)
                                                    <th>{{ $item->attribute_name }}</th>
                                                @endforeach
                                                <th>{{ __('Terbayar') }}</th>
                                                <th>{{ __('Total ') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            <tr>
                                                <td>
                                                    {{ $no++ }}
                                                </td>
                                                <td>
                                                    {{ $user->name }}
                                                </td>
                                                @foreach ($student as $item)
                                                    <td class="text-center bg-white">
                                                        @if ($item->status == 'Paid')
                                                            <p>Rp{{ number_format($item->attribute_price, 0, ',', '.') }}
                                                                <br>(Lunas)
                                                            </p>
                                                        @else
                                                            <label><input class="checkbox" type="checkbox"
                                                                    name="attribute_id[]" value="{{ $item->id }}">
                                                                <p>Rp{{ number_format($item->attribute_price, 0, ',', '.') }}
                                                                </p>
                                                        @endif
                                                        </label>
                                                    </td>
                                                @endforeach
                                                @php
                                                    $totalattributePrice = 0;
                                                    $totalBilling = 0;
                                                @endphp
                                                <td>
                                                    @foreach ($user->paymentAttribute as $attribute)
                                                        @php
                                                            $totalattributePrice += $attribute->price;
                                                        @endphp
                                                    @endforeach
                                                    Rp{{ number_format($totalattributePrice, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    @foreach ($user->paymentAttribute as $attribute)
                                                        @php
                                                            $totalattributePrice += $attribute->attribute_price;
                                                        @endphp
                                                    @endforeach
                                                    Rp{{ number_format($totalattributePrice, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="py-4">
                                        <hr class="py-3">
                                        {{-- <h5 class="fw-bold">
                                            Total : <span id="totalChecked">0</span>
                                        </h5> --}}
                                        <div class="py-3">
                                            <button class="btn btn-primary button-payment" onclick="submitForm()"
                                                style="display: none;">Bayar
                                                Sekarang</button>

                                        </div>
                                    </div>
                                </div>
                            </form>
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
@endsection

@push('scripts')
    <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
    <script>
        function submitForm() {
            // Add any additional validation logic if needed

            // Trigger form submission
            document.getElementById('paymentForm').submit();
        }
    </script>
    <script>
        // Mendapatkan semua elemen checkbox
        const checkboxes = document.querySelectorAll('.checkbox');

        // Menambahkan event listener untuk setiap checkbox
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', updateTotalChecked);
        });

        // Fungsi untuk memformat angka ke format Rupiah
        function formatRupiah(angka) {
            let reverse = angka.toString().split('').reverse().join('');
            let ribuan = reverse.match(/\d{1,3}/g);
            let formatted = ribuan.join('.').split('').reverse().join('');
            return 'Rp' + formatted;
        }

        // Fungsi untuk memeriksa apakah setidaknya satu checkbox dicentang
        function isAnyCheckboxChecked() {
            let checked = false;
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    checked = true;
                }
            });
            return checked;
        }

        function updateTotalChecked() {
            // Menghitung jumlah checkbox yang terpilih dan pertambahan nilai value
            let totalChecked = 0;
            let anyCheckboxChecked = false;

            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    totalChecked += parseInt(checkbox.value, 10);
                    anyCheckboxChecked = true;
                }
            });

            // Menampilkan jumlah pertambahan nilai value checkbox yang terpilih
            document.getElementById('totalChecked').textContent = formatRupiah(totalChecked);

            // Menyembunyikan atau menampilkan tombol "Bayar Sekarang" berdasarkan keberadaan checkbox yang dicentang
            const bayarSekarangButton = document.querySelector('.button-payment');
            bayarSekarangButton.style.display = anyCheckboxChecked ? 'block' : 'none';
        }
    </script>
@endpush
