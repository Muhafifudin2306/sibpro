@extends('layouts.admin.app')

@section('title_page')
    Laman Pembayaran
@endsection

@section('content')
    @push('styles')
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endpush

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Pembayaran SPP') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item active">{{ __('Pemasukan') }}</div>
                        <div class="breadcrumb-item active">{{ __('SPP') }}</div>
                        <div class="breadcrumb-item">{{ __('Pembayaran') }}</div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="invoice-title">
                                        <h5>Slip Pembayaran SPP</h5>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <address>
                                                <strong>Billed To:</strong><br>
                                                {{ $order->user->name }}<br>
                                                {{ $order->user->nis }}<br>
                                            </address>
                                        </div>
                                        <div class="col-md-6 text-md-right">
                                            <address>
                                                <strong>Shipped To:</strong><br>
                                                SMK BP Darul Ulum<br>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="section-title">Order Summary</div>
                                    <p class="section-lead">All items here cannot be deleted.</p>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-md">
                                            <tr>
                                                <th data-width="40">#</th>
                                                <th>Invoice Number</th>
                                                <th>Item</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Totals</th>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>{{ $order->invoice_number }}</td>
                                                <td>{{ $order->credit->credit_name }}</td>
                                                <td>
                                                    Rp{{ number_format($order->credit->credit_price, 0, ',', '.') }}</td>
                                                <td>1</td>
                                                <td>
                                                    Rp{{ number_format($order->credit->credit_price, 0, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-lg-8">
                                        </div>
                                        <div class="col-lg-4 text-right">
                                            <hr class="mt-2 mb-2">
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Total</div>
                                                <div class="invoice-detail-value invoice-detail-value-lg">
                                                    Rp{{ number_format($order->credit->credit_price, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="text-md-right">
                            <div class="float-lg-left mb-lg-0 mb-3">
                                <button class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i>
                                    Print</button>
                                {{-- <button class="btn btn-danger btn-icon icon-left"><i class="fas fa-times"></i>
                                    Cancel</button> --}}
                            </div>
                            <button class="btn btn-primary btn-icon icon-left" id="pay-button"><i
                                    class="fas fa-credit-card"></i>
                                Bayar Sekarang</button>
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
    <script type="text/javascript">
        // For example trigger on button clicked, or any time you need
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function() {
            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    /* You may add your own implementation here */
                    // alert("payment success!");
                    window.location.href = '/income/credit/detail/{{ $order->user->uuid }}';
                    console.log(result);
                },
                onPending: function(result) {
                    /* You may add your own implementation here */
                    alert("wating your payment!");
                    console.log(result);
                },
                onError: function(result) {
                    /* You may add your own implementation here */
                    alert("payment failed!");
                    console.log(result);
                },
                onClose: function() {
                    /* You may add your own implementation here */
                    alert('you closed the popup without finishing the payment');
                }
            })
        });
    </script>
@endpush
