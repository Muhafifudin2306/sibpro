@extends('layouts.admin.app')

@section('title_page', 'Packages Add');

@section('content')
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin :students="$students"></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header d-flex justify-content-lg-between">
                    <div class="title">
                        <h1>{{ __('Edit Blangko Tagihan') }}</h1>
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

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>{{ __('Edit Blangko Tagihan') }}</h4>
                        </div>
                        <div class="card-body pb-5">
                            <form class="update-form" data-action="{{ url('/enrollment/update/' . $credit->id) }} }}"
                                method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('NIS') }}</label>
                                                <input type="text" class="form-control" value="{{ $credit->user->nis }}"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Nama Siswa') }}</label>
                                                <input type="text" class="form-control" value="{{ $credit->user->name }}"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Pembayaran') }}</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $credit->attribute->attribute_name ?? $credit->credit->credit_name }}"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Tipe Pembayaran') }}</label>
                                                <input type="text" class="form-control" value="{{ $credit->type }}"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Tahun') }}</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $credit->year->year_name }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Nominal') }}</label>
                                                <input type="text" class="form-control currency-format" name="price"
                                                    id="attribute_name" value="{{ round($credit->price) }}" required="">
                                                <input type="hidden" name="price" class="currency-raw">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Status') }}</label>
                                                <select name="status" id="" class="form-control">
                                                    @if ($credit->status == 'Paid')
                                                        <option value="Paid" selected>Lunas</option>
                                                        <option value="Unpaid">Belum Lunas</option>
                                                    @elseif($credit->status == 'Unpaid')
                                                        <option value="Paid">Lunas</option>
                                                        <option value="Unpaid" selected>Belum Lunas</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{ __('Simpan Data') }}</button>
                                </div>
                            </form>
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
@endsection


@push('scripts')
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

                            window.location.href =
                                '/enrollment/{{ $credit->user->class_id }}';
                        })
                        .catch(error => {
                            Notiflix.Notify.failure('Error:', error);
                        });
                });
            });
        });
    </script>
@endpush
