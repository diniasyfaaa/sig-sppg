<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"
>
    <!-- Leaflet CSS -->
    <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-dropdown.css') }}">
    @yield('styles')

</head>

<body class="admin-layout">

    {{-- Navbar --}}
    @include('components.navbar-admin')

    <div class="admin-shell">

        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Konten --}}
        <main class="admin-content @yield('content-class')">
            @yield('content')
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle =
        document.getElementById('sidebarToggle');

    const profileButton =
        document.getElementById('adminProfileButton');

    const profileDropdown =
        document.getElementById('adminProfileDropdown');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-collapsed');

            setTimeout(function () {
                window.dispatchEvent(new Event('resize'));
            }, 320);
        });
    }
});
</script>

        <script src="{{ asset('js/custom-dropdown.js') }}"></script>
    @yield('scripts')

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    flatpickr('#tanggal_peresmian', {
        allowInput: true,
        clickOpens: true,
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd/m/Y',
        altInputClass: 'form-control',
    });
</script>

</body>
</html>