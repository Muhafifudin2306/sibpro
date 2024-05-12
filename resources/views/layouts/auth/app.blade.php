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
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    @stack('styles')
</head>

<body>
    <main>
        <div id="app">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
    <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script>
        window.addEventListener('beforeinstallprompt', (e) => {
            // Tampilkan prompt untuk menambahkan aplikasi ke layar utama
            e.prompt();
        });
    </script>

</body>

</html>
