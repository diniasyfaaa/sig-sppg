@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

    <div class="dashboard-header">

        <div class="header-left">
            <h2>
                Dashboard Admin
            </h2>
            <p>
                Sistem Informasi Geografis Data SPPG Kecamatan Cileunyi
            </p>
        </div>

    </div>

    <div class="dashboard-cards">
        <div class="dashboard-card">

            <div class="icon-box blue">
                <i class="bi bi-house-door-fill"></i>
            </div>

            <div>
                <h5>Total Desa</h5>
                <h2>{{ $totalDesa }}</h2>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon-box green">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <h5>Total SPPG</h5>
                <h2>{{ $totalSppg }}</h2>
            </div>
        </div>

        <div class="dashboard-card">

            <div class="icon-box orange">
                <i class="bi bi-people-fill"></i>
            </div>

            <div>
                <h5>Total Sasaran</h5>
                <h2>{{ $totalSasaran }}</h2>
            </div>

        </div>
        <div class="dashboard-card">
            <div class="icon-box purple">
                <i class="bi bi-basket2-fill"></i>
            </div>

            <div>
                <h5>Total Kapasitas</h5>
                <h2>{{ number_format($totalKapasitas) }}</h2>
            </div>

        </div>
        <div class="dashboard-card">
            <div class="icon-box red">
                <i class="bi bi-person-workspace"></i>
            </div>
            <div>
                <h5>Total Pegawai</h5>
                <h2>{{ $totalPegawai }}</h2>
            </div>
        </div>

    </div>
    <div class="row mt-4 align-items-stretch">

        <!-- PETA -->
        <div class="col-lg-8 d-flex">

            <div class="map-card h-100 w-100">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h4>Peta Sebaran SPPG</h4>

                    <a href="{{ route('peta.index') }}" class="btn-main-sm">
                        <i class="bi bi-map"></i>
                        Lihat Selengkapnya
                    </a>

                </div>

                <div id="map"></div>

            </div>

        </div>

        <!-- SPPG TERBARU -->
        <div class="col-lg-4 d-flex">

            <div class="latest-card h-100 w-100">

                <h4>SPPG Terbaru</h4>

                <hr>

                <div class="latest-list">

                    @foreach($sppgTerbaru as $item)

                        <a href="{{ route('sppg.show', $item->id) }}" class="text-decoration-none text-dark">

                            <div class="latest-item">

                                @if($item->foto_depan)
                                    <img src="{{ asset('storage/' . $item->foto_depan) }}" class="latest-photo">
                                @else
                                    <div class="latest-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif

                                <div class="latest-info">

                                    <h6>{{ $item->nama_sppg }}</h6>

                                    <small>{{ $item->desa->nama_desa }}</small>

                                    <p>{{ $item->alamat }}</p>

                                    <span>
                                        {{ \Carbon\Carbon::parse($item->tanggal_peresmian)->format('d M Y') }}
                                    </span>

                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

                <a href="{{ route('admin.statistik', ['analisis' => 'peresmian']) }}" class="lihat-detail">
                    Lihat Detail
                </a>

            </div>

        </div>

    </div>

    {{-- =====================================================
    STATISTIK PER DESA
    ===================================================== --}}
    <section class="dashboard-statistics-section">

        <div class="dashboard-section-heading">
            <h2>Statistik Per Desa</h2>

            <p>
                Ringkasan data SPPG berdasarkan desa
            </p>
        </div>

        <div class="dashboard-statistics-grid">

            {{-- Jumlah SPPG --}}
            <a href="{{ route('admin.statistik', [
        'analisis' => 'jumlah_sppg'
    ]) }}" class="dashboard-stat-card">
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

            {{-- Kapasitas --}}
            <a href="{{ route('admin.statistik', [
        'analisis' => 'kapasitas'
    ]) }}" class="dashboard-stat-card">
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

            {{-- Pegawai --}}
            <a href="{{ route('admin.statistik', [
        'analisis' => 'pegawai'
    ]) }}" class="dashboard-stat-card">
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
    <section class="dashboard-status-section">

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

                <div class="status-legend-item">
                    <span class="status-dot status-dot-active"></span>

                    <div>
                        <span class="status-label">Aktif</span>

                        <strong id="activeStatusValue">
                            {{ $totalAktif }} SPPG
                        </strong>

                        <small id="activeStatusPercentage">
                            0%
                        </small>
                    </div>
                </div>

                <div class="status-legend-item">
                    <span class="status-dot status-dot-inactive"></span>

                    <div>
                        <span class="status-label">Tidak Aktif</span>

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
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var map = L.map('map').setView([-6.9385, 107.7178], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);
        @foreach($sppg as $item)
            @if($item->latitude && $item->longitude)
                L.marker([{{ $item->latitude }}, {{ $item->longitude }}])
                    .addTo(map)
                    .bindPopup(`
                            <div class="popup-card">

                                <div class="popup-header">

                                    <img
                                        src="{{ $item->foto_depan ? asset('storage/' . $item->foto_depan) : asset('images/no-image.png') }}"
                                        class="popup-photo">

                                    <div class="popup-info">

                                        <div class="popup-title">
                                            {{ $item->nama_sppg }}
                                        </div>

                                        <div class="popup-desa">
                                            {{ $item->desa->nama_desa }}
                                        </div>

                                        @if($item->status == 'Aktif')
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

                                <a href="{{ route('sppg.show', $item->id) }}"
                                class="popup-detail">

                                    Lihat Detail

                                </a>

                            </div>
                            `);
            @endif
        @endforeach

        document.addEventListener('DOMContentLoaded', function () {

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
            | Doughnut status SPPG
            |--------------------------------------------------------------------------
            */
            const totalAktif = Number(@json($totalAktif)) || 0;
            const totalTidakAktif = Number(@json($totalTidakAktif)) || 0;
            const totalStatus = totalAktif + totalTidakAktif;

            const persentaseAktif = totalStatus > 0
                ? ((totalAktif / totalStatus) * 100).toFixed(1)
                : 0;

            const persentaseTidakAktif = totalStatus > 0
                ? ((totalTidakAktif / totalStatus) * 100).toFixed(1)
                : 0;

            document.getElementById(
                'activeStatusPercentage'
            ).textContent = `${persentaseAktif}%`;

            document.getElementById(
                'inactiveStatusPercentage'
            ).textContent = `${persentaseTidakAktif}%`;

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
                                        const value = context.parsed;

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
    </script>

@endsection