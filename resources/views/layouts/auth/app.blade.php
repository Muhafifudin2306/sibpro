<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title> @yield('title_page') | SIBPro</title>
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link
        href="data:image/x-icon;base64,AAABAAEAEBAQAAEABAAoAQAAFgAAACgAAAAQAAAAIAAAAAEABAAAAAAAgAAAAAAAAAAAAAAAEAAAAAAAAADdZgUA/vz7AO+6kADcZAAA////AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMzMzMzMzMzMzMzMzMzMzMzMzMzREQ0QzMzMzRERERDMzMzREMzREMzMzNEMzNEQzMzM0QzMzRDMzMzREMzREMzMzNERAREQzMzMzRERERDMzMzM0RBJEMzMzMzMzMkQzMzMzMzMyRDMzMzMzMzJEMzMzMzMzMzMzMzMzMzMzMzMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA"
        rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/modules/notiflix/src/notiflix.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}"> --}}
    @stack('styles')

</head>

<body>
    <main>
        <div id="app">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
    {{-- <script src="{{ asset('assets/modules/jquery.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/modules/popper.js') }}"></script>
    <script src="{{ asset('assets/modules/tooltip.js') }}"></script>

    <script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>

    <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/stisla.js') }}"></script> --}}
    <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/modules/notiflix/src/notiflix.js') }}"></script>
    <script src="{{ asset('assets/js/page/forms-advanced-forms.js') }}"></script> --}}
</body>

</html>
