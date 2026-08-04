@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')

{{-- =====================================================
     HERO
===================================================== --}}
@include('components.hero')


{{-- =====================================================
     KONTEN DASHBOARD USER
===================================================== --}}
<section class="user-dashboard-content scroll-reveal" id="ringkasan-dashboard">

    <div class="container-fluid px-4 px-lg-5 py-5">

        {{-- =====================================================
             HEADER
        ===================================================== --}}


        {{-- =====================================================
             RINGKASAN DATA
        ===================================================== --}}
<div class="user-summary-layout">

    {{-- Card besar: Total Desa --}}
    <div class="user-summary-main-card">

        <span class="user-summary-label">
            Total Desa
        </span>

        <div class="user-summary-main-value">
            <strong>{{ number_format($totalDesa) }}</strong>
            <small>Desa</small>
        </div>

    </div>


    {{-- Empat card kecil --}}
    <div class="user-summary-small-grid">

        {{-- Total SPPG --}}
        <div class="user-summary-small-card">

            <span class="user-summary-label">
                Total SPPG
            </span>

            <div class="user-summary-small-value">
                <strong>{{ number_format($totalSppg) }}</strong>
                <small>Unit</small>
            </div>

        </div>


        {{-- Total Kapasitas --}}
        <div class="user-summary-small-card">

            <span class="user-summary-label">
                Total Kapasitas
            </span>

            <div class="user-summary-small-value">
                <strong>{{ number_format($totalKapasitas) }}</strong>
                <small>Porsi/Hari</small>
            </div>

        </div>


        {{-- Total Sasaran --}}
        <div class="user-summary-small-card">

            <span class="user-summary-label">
                Total Sasaran
            </span>

            <div class="user-summary-small-value">
                <strong>{{ number_format($totalSasaran) }}</strong>
                <small>Tempat</small>
            </div>

        </div>


        {{-- Total Pegawai --}}
        <div class="user-summary-small-card">

            <span class="user-summary-label">
                Total Pegawai
            </span>

            <div class="user-summary-small-value">
                <strong>{{ number_format($totalPegawai) }}</strong>
                <small>Jiwa</small>
            </div>

        </div>

    </div>

</div>

        {{-- =====================================================
             PETA DAN SPPG TERBARU
        ===================================================== --}}
        <div class="row mt-4 g-4 align-items-stretch scroll-reveal">

            {{-- PETA --}}
            <div class="col-lg-8 d-flex">

                <div class="map-card h-100 w-100">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h4 class="mb-0">
                            Peta Sebaran SPPG
                        </h4>

                        <a
                            href="{{ route('user.peta') }}"
                            class="btn-main-sm"
                        >
                            <i class="bi bi-map"></i>
                            Lihat Selengkapnya
                        </a>

                    </div>

                    <div id="map"></div>

                </div>

            </div>


            {{-- SPPG TERBARU --}}
            <div class="col-lg-4 d-flex">

                <div class="latest-card h-100 w-100">

                    <h4>SPPG Terbaru</h4>

                    <hr>

                    <div class="latest-list">

                        @forelse($sppgTerbaru as $item)

                            <a
                                href="{{ route('user.sppg.show', $item->id) }}"
                                class="text-decoration-none text-dark"
                            >

                                <div class="latest-item">

                                    @if($item->foto_depan)

                                        <img
                                            src="{{ asset('storage/' . $item->foto_depan) }}"
                                            class="latest-photo"
                                            alt="{{ $item->nama_sppg }}"
                                        >

                                    @else

                                        <div class="latest-placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>

                                    @endif

                                    <div class="latest-info">

                                        <h6>
                                            {{ $item->nama_sppg }}
                                        </h6>

                                        <small>
                                            {{ optional($item->desa)->nama_desa ?? '-' }}
                                        </small>

                                        <p>
                                            {{ \Illuminate\Support\Str::limit($item->alamat, 55) }}
                                        </p>

                                        <span>
                                            @if($item->tanggal_peresmian)
                                                {{ \Carbon\Carbon::parse($item->tanggal_peresmian)->translatedFormat('d M Y') }}
                                            @else
                                                Tanggal belum tersedia
                                            @endif
                                        </span>

                                    </div>

                                </div>

                            </a>

                        @empty

                            <div class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Belum ada data SPPG.
                            </div>

                        @endforelse

                    </div>

                    <a
                        href="{{ route('user.statistik', [
                            'analisis' => 'peresmian'
                        ]) }}"
                        class="lihat-detail"
                    >
                        Lihat Detail
                    </a>

                </div>

            </div>

        </div>


        {{-- =====================================================
             STATISTIK PER DESA
        ===================================================== --}}
        <section class="dashboard-statistics-section scroll-reveal">

            <div class="dashboard-section-heading">

                <h2>Statistik Per Desa</h2>

                <p>
                    Ringkasan data SPPG berdasarkan desa
                </p>

            </div>

            <div class="dashboard-statistics-grid">

                {{-- Jumlah SPPG --}}
                <a
                    href="{{ route('user.statistik', [
                        'analisis' => 'jumlah_sppg'
                    ]) }}"
                    class="dashboard-stat-card"
                >

                    <div class="stat-card-header">

                        <div>
                            <h3>Jumlah SPPG Per Desa</h3>

                            <p>
                                Perbandingan jumlah SPPG pada setiap desa
                            </p>
                        </div>

                        <span class="stat-card-arrow">
                            <i class="bi bi-arrow-up-right"></i>
                        </span>

                    </div>

                    <div class="dashboard-chart-wrapper">
                        <canvas id="dashboardJumlahSppgChart"></canvas>
                    </div>

                    <span class="stat-card-link">
                        Lihat statistik lengkap
                        <i class="bi bi-arrow-right"></i>
                    </span>

                </a>


                {{-- Kapasitas Produksi --}}
                <a
                    href="{{ route('user.statistik', [
                        'analisis' => 'kapasitas'
                    ]) }}"
                    class="dashboard-stat-card"
                >

                    <div class="stat-card-header">

                        <div>
                            <h3>Kapasitas Produksi Per Hari</h3>

                            <p>
                                Total kapasitas produksi setiap desa
                            </p>
                        </div>

                        <span class="stat-card-arrow">
                            <i class="bi bi-arrow-up-right"></i>
                        </span>

                    </div>

                    <div class="dashboard-chart-wrapper">
                        <canvas id="dashboardKapasitasChart"></canvas>
                    </div>

                    <span class="stat-card-link">
                        Lihat statistik lengkap
                        <i class="bi bi-arrow-right"></i>
                    </span>

                </a>


                {{-- Jumlah Pegawai --}}
                <a
                    href="{{ route('user.statistik', [
                        'analisis' => 'pegawai'
                    ]) }}"
                    class="dashboard-stat-card"
                >

                    <div class="stat-card-header">

                        <div>
                            <h3>Jumlah Pegawai SPPG</h3>

                            <p>
                                Total pegawai SPPG pada setiap desa
                            </p>
                        </div>

                        <span class="stat-card-arrow">
                            <i class="bi bi-arrow-up-right"></i>
                        </span>

                    </div>

                    <div class="dashboard-chart-wrapper">
                        <canvas id="dashboardPegawaiChart"></canvas>
                    </div>

                    <span class="stat-card-link">
                        Lihat statistik lengkap
                        <i class="bi bi-arrow-right"></i>
                    </span>

                </a>

            </div>

        </section>


        {{-- =====================================================
             STATUS SPPG
        ===================================================== --}}
        <section class="dashboard-status-section scroll-reveal">

            <div class="dashboard-section-heading">

                <h2>Persentase Status SPPG</h2>

                <p>
                    Perbandingan SPPG aktif dan tidak aktif
                </p>

            </div>

            <div class="dashboard-status-card">

                <div class="dashboard-status-chart">
                    <canvas id="dashboardStatusChart"></canvas>
                </div>

                <div class="dashboard-status-legend">

                    {{-- Aktif --}}
                    <div class="status-legend-item">

                        <span class="status-dot status-dot-active"></span>

                        <div>
                            <span class="status-label">
                                Aktif
                            </span>

                            <strong id="activeStatusValue">
                                {{ $totalAktif }} SPPG
                            </strong>

                            <small id="activeStatusPercentage">
                                0%
                            </small>
                        </div>

                    </div>


                    {{-- Tidak Aktif --}}
                    <div class="status-legend-item">

                        <span class="status-dot status-dot-inactive"></span>

                        <div>
                            <span class="status-label">
                                Tidak Aktif
                            </span>

                            <strong id="inactiveStatusValue">
                                {{ $totalTidakAktif }} SPPG
                            </strong>

                            <small id="inactiveStatusPercentage">
                                0%
                            </small>
                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</section>

@endsection


@section('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Peta Leaflet
    |--------------------------------------------------------------------------
    */

    const mapElement = document.getElementById('map');

    if (mapElement) {

        const map = L.map('map').setView(
            [-6.9385, 107.7178],
            13
        );

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                attribution: '&copy; OpenStreetMap'
            }
        ).addTo(map);

        @foreach($sppg as $item)

            @if($item->latitude && $item->longitude)

                L.marker([
                    {{ $item->latitude }},
                    {{ $item->longitude }}
                ])
                .addTo(map)
                .bindPopup(`
                    <div class="popup-card">

                        <div class="popup-header">

                            <img
                                src="{{ $item->foto_depan
                                    ? asset('storage/' . $item->foto_depan)
                                    : asset('images/no-image.png') }}"
                                class="popup-photo"
                                alt="{{ $item->nama_sppg }}"
                            >

                            <div class="popup-info">

                                <div class="popup-title">
                                    {{ $item->nama_sppg }}
                                </div>

                                <div class="popup-desa">
                                    {{ optional($item->desa)->nama_desa ?? '-' }}
                                </div>

                                @if($item->status === 'Aktif')

                                    <span class="popup-status aktif">
                                        Aktif
                                    </span>

                                @else

                                    <span class="popup-status nonaktif">
                                        Tidak Aktif
                                    </span>

                                @endif

                            </div>

                        </div>

                        <a
                            href="{{ route('user.sppg.show', $item->id) }}"
                            class="popup-detail"
                        >
                            Lihat Detail
                        </a>

                    </div>
                `);

            @endif

        @endforeach

        setTimeout(function () {
            map.invalidateSize();
        }, 250);
    }


    /*
    |--------------------------------------------------------------------------
    | Data Statistik
    |--------------------------------------------------------------------------
    */

    const statistikDesa = @json($statistikDesa);

    const labels = statistikDesa.map(function (item) {
        return item.nama_desa;
    });

    const jumlahSppg = statistikDesa.map(function (item) {
        return Number(item.jumlah_sppg) || 0;
    });

    const kapasitas = statistikDesa.map(function (item) {
        return Number(item.total_kapasitas) || 0;
    });

    const pegawai = statistikDesa.map(function (item) {
        return Number(item.total_pegawai) || 0;
    });


    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Grafik Batang
    |--------------------------------------------------------------------------
    */

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,

        plugins: {
            legend: {
                display: false
            }
        },

        scales: {
            x: {
                grid: {
                    display: false
                },

                ticks: {
                    color: '#667085',

                    font: {
                        size: 10
                    }
                }
            },

            y: {
                beginAtZero: true,

                grid: {
                    color: 'rgba(18, 57, 87, 0.08)'
                },

                ticks: {
                    precision: 0,
                    color: '#667085'
                }
            }
        }
    };


    /*
    |--------------------------------------------------------------------------
    | Membuat Grafik Batang
    |--------------------------------------------------------------------------
    */

    function createBarChart(canvasId, label, data) {

        const canvas = document.getElementById(canvasId);

        if (!canvas) {
            return;
        }

        new Chart(canvas, {
            type: 'bar',

            data: {
                labels: labels,

                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: '#2167f3',
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },

            options: commonOptions
        });
    }

    createBarChart(
        'dashboardJumlahSppgChart',
        'Jumlah SPPG',
        jumlahSppg
    );

    createBarChart(
        'dashboardKapasitasChart',
        'Kapasitas',
        kapasitas
    );

    createBarChart(
        'dashboardPegawaiChart',
        'Jumlah Pegawai',
        pegawai
    );


    /*
    |--------------------------------------------------------------------------
    | Doughnut Status SPPG
    |--------------------------------------------------------------------------
    */

    const totalAktif =
        Number(@json($totalAktif)) || 0;

    const totalTidakAktif =
        Number(@json($totalTidakAktif)) || 0;

    const totalStatus =
        totalAktif + totalTidakAktif;

    const persentaseAktif =
        totalStatus > 0
            ? ((totalAktif / totalStatus) * 100).toFixed(1)
            : 0;

    const persentaseTidakAktif =
        totalStatus > 0
            ? ((totalTidakAktif / totalStatus) * 100).toFixed(1)
            : 0;

    const activePercentageElement =
        document.getElementById('activeStatusPercentage');

    const inactivePercentageElement =
        document.getElementById('inactiveStatusPercentage');

    if (activePercentageElement) {
        activePercentageElement.textContent =
            `${persentaseAktif}%`;
    }

    if (inactivePercentageElement) {
        inactivePercentageElement.textContent =
            `${persentaseTidakAktif}%`;
    }

    const statusCanvas =
        document.getElementById('dashboardStatusChart');

    if (statusCanvas) {

        new Chart(statusCanvas, {
            type: 'doughnut',

            data: {
                labels: [
                    'Aktif',
                    'Tidak Aktif'
                ],

                datasets: [{
                    data: [
                        totalAktif,
                        totalTidakAktif
                    ],

                    backgroundColor: [
                        '#16a34a',
                        '#ef2b2d'
                    ],

                    borderColor: '#ffffff',
                    borderWidth: 4,
                    hoverOffset: 6
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        callbacks: {
                            label: function (context) {

                                const value =
                                    context.parsed;

                                const percentage =
                                    totalStatus > 0
                                        ? (
                                            value /
                                            totalStatus *
                                            100
                                        ).toFixed(1)
                                        : 0;

                                return `${context.label}: ` +
                                    `${value} SPPG (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

});

document.addEventListener('DOMContentLoaded', function () {

    const revealElements =
        document.querySelectorAll('.scroll-reveal');

    const revealObserver = new IntersectionObserver(
        function (entries, observer) {

            entries.forEach(function (entry) {

                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');

                observer.unobserve(entry.target);
            });

        },
        {
            threshold: 0.12,
            rootMargin: '0px 0px -60px 0px'
        }
    );

    revealElements.forEach(function (element) {
        revealObserver.observe(element);
    });

});
</script>

@endsection