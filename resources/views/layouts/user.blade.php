<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>@yield('title', 'SIG SPPG')</title>

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    {{-- Leaflet --}}
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >

    {{--
        CSS dashboard dan komponen bersama.

        Dashboard admin saat ini memakai styling dari admin.css.
        Kita panggil juga di user agar card, map, chart, dan tabel
        mempunyai tampilan yang sama.
    --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/admin.css') }}"
    >

    {{--
        CSS khusus navbar, hero, footer, serta penyesuaian user.
        Letakkan setelah admin.css agar dapat menimpa style tertentu.
    --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/user.css') }}"
    >

    {{-- CSS khusus masing-masing halaman --}}
    @yield('styles')

</head>

<body class="user-layout">

    {{-- Navbar publik --}}
    @include('components.navbar')

    {{-- Isi halaman --}}
    <main class="user-content">
        @yield('content')
    </main>

    {{-- Footer publik --}}
    @include('components.footer')

    {{-- Bootstrap JS --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js">
    </script>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- JavaScript khusus halaman --}}
    @yield('scripts')

</body>

</html>