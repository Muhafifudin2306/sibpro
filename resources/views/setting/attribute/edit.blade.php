@extends('layouts.admin.app')

@section('title_page', 'Packages Edit');

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.css') }}">
    @endpush

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Edit Paket') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('General Setting') }}</div>
                        <div class="breadcrumb-item">{{ __('Paket') }}</div>
                        <div class="breadcrumb-item active">{{ __('Edit') }}</div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>{{ __('Edit Paket') }}</h4>
                            <a href="{{ url('/setting/packages') }}"> {{ __('Close') }} </a>
                        </div>
                        <div class="card-body pb-5">
                            <form class="update-form" data-action="{{ url('setting/packages/update/' . $category->id) }}"
                                method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>{{ __('Nama Paket') }}</label>
                                    <select class="form-control select2" name="category_id">
                                        <option value="{{ $category->id }}" selected>{{ $category->category_name }}
                                        </option>
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Atribut Daftar Ulang') }}</label>
                                    <div class="checkbox-list">
                                        @foreach ($allAttribute as $item)
                                            <div class="py-1 mx-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox"
                                                        name="attribute_id[]" id="{{ $item->slug }}"
                                                        value="{{ $item->id }}"
                                                        {{ in_array($item->id, $attributes->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="{{ $item->slug }}">{{ $item->attribute_name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Atribut SPP') }}</label>
                                    <div class="checkbox-list">
                                        @foreach ($allCredit as $item)
                                            <div class="py-1 mx-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" name="credit_id[]"
                                                        value="{{ $item->id }}"
                                                        {{ in_array($item->id, $credits->pluck('id')->toArray()) ? 'checked' : '' }}
                                                        id="{{ $item->slug }}">
                                                    <label class="custom-control-label"
                                                        for="{{ $item->slug }}">{{ $item->credit_name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">{{ __('Simpan Data') }}</button>
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
    @can('access-packageEdit')
        <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
    @endcan
    @can('access-packageUpdate')
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
                                Notiflix.Notify.success("Data paket berhasil diperbarui!", {
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
@endpush
