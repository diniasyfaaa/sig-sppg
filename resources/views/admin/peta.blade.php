@extends('layouts.admin')

@section('title', 'Peta Sebaran SPPG')

@section('content-class', 'admin-content-map')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <link rel="stylesheet" href="{{ asset('css/peta-sebaran.css') }}">
@endsection

@section('content')

    <div class="map-fullscreen-page">

        {{-- Peta utama --}}
        <div id="mapSebaran"></div>

        {{-- Search mengambang di atas peta --}}
        <div class="map-search-card">

            <div class="map-search-wrapper">

                <i class="bi bi-search map-search-icon"></i>

                <input type="text" id="searchSppg" class="map-search-input"
                    placeholder="Cari nama SPPG, ketua, yayasan, desa, atau alamat..." autocomplete="off">

                <button type="button" class="map-search-clear d-none" id="clearSearch" title="Hapus pencarian">
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>

            {{-- Hasil pencarian --}}
            <div class="search-result-list d-none" id="searchResultList"></div>
        </div>

        {{-- Tombol kembali mengambang --}}
        <a href="{{ route('admin.dashboard') }}" class="floating-back-button">
            <i class="bi bi-arrow-left"></i>
            <span>Dashboard</span>
        </a>

        {{-- Legend sederhana --}}
        <div class="map-legend">
            <div class="legend-title">Keterangan</div>

            <div class="legend-item">
                <span class="legend-dot active"></span>
                SPPG Aktif
            </div>

            <div class="legend-item">
                <span class="legend-dot inactive"></span>
                SPPG Tidak Aktif
            </div>
        </div>

    </div>

@endsection

@section('scripts')

    <script>
        window.sppgData = @json($sppg);
    </script>

    <script src="{{ asset('js/peta-sebaran.js') }}"></script>

@endsection