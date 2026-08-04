@extends('layouts.user')

@section('title', 'Statistik')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/statistik.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">

@endsection

@section('content')

<div class="user-page-container">
    {{-- Header --}}
    <div class="dashboard-header">
        <div class="header-left">
            <h2>Statistik Monitoring</h2>
            <p>Analisis Data SPPG Kecamatan Cileunyi</p>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="stat-summary">

        <div class="summary-card">
            <div class="summary-icon">
                <i class="bi bi-building"></i>
            </div>

            <div>
                <span>Total SPPG</span>
                <strong id="summarySppg">0</strong>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="bi bi-people-fill"></i>
            </div>

            <div>
                <span>Total Pegawai</span>
                <strong id="summaryPegawai">0</strong>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="bi bi-box-seam-fill"></i>
            </div>

            <div>
                <span>Total Kapasitas</span>
                <strong id="summaryKapasitas">0</strong>
            </div>
        </div>

    </div>

    {{-- Filter --}}
    <div class="filter-card">
        <div class="row g-3">

            {{-- Desa --}}
            <div class="col-lg-4 col-md-6">
                <label for="desa" class="form-label">
                    Desa
                </label>

                <select class="form-select" id="desa">
                    <option value="all">Semua Desa</option>

                    @foreach($desa as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->nama_desa }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Analisis --}}
            <div class="col-lg-4 col-md-6">
                <label for="analisis" class="form-label">
                    Analisis
                </label>

                <select class="form-select" id="analisis">

                    <option value="jumlah_sppg"
                        {{ request('analisis') === 'jumlah_sppg' ? 'selected' : '' }}>
                        Jumlah SPPG
                    </option>

                    <option value="peresmian"
                        {{ request('analisis') === 'peresmian' ? 'selected' : '' }}>
                        Peresmian
                    </option>

                    <option value="kapasitas"
                        {{ request('analisis') === 'kapasitas' ? 'selected' : '' }}>
                        Kapasitas Produksi
                    </option>

                    <option value="pegawai"
                        {{ request('analisis') === 'pegawai' ? 'selected' : '' }}>
                        Jumlah Pegawai
                    </option>

                </select>
            </div>

            {{-- Urutan data --}}
            <div class="col-lg-4 col-md-6">
                <label for="urutanData" class="form-label">
                    Urutan
                </label>

                <select class="form-select" id="urutanData">
                    {{-- Opsi akan diatur otomatis oleh JavaScript --}}
                </select>
            </div>

        </div>
    </div>

{{-- Toolbar Grafik --}}
<div class="chart-toolbar mt-4">

    <div class="chart-type">

        <button
            type="button"
            class="chart-btn active"
            id="btnBar"
            data-chart="bar"
        >
            <i class="bi bi-bar-chart-fill"></i>
            Bar Chart
        </button>

        <button
            type="button"
            class="chart-btn"
            id="btnPie"
            data-chart="pie"
        >
            <i class="bi bi-pie-chart-fill"></i>
            Pie Chart
        </button>

        <button
            type="button"
            class="chart-btn"
            id="btnLine"
            data-chart="line"
        >
            <i class="bi bi-graph-up-arrow"></i>
            Line Chart
        </button>

    </div>

    <div class="chart-limit-wrapper">

        <select
            class="form-select chart-limit-select"
            id="limitData"
        >
            <option value="5">5 Data</option>
            <option value="10" selected>10 Data</option>
            <option value="50">50 Data</option>
            <option value="all">Semua Data</option>
        </select>

    </div>

    <button
    type="button"
    class="export-pdf-btn"
    id="exportPdfBtn"
>
    <i class="bi bi-file-earmark-pdf-fill"></i>
    Export PDF
</button>

</div>

    {{-- Grafik --}}
    <div class="chart-card mt-4">

        <div class="chart-card-header">

            <div>
                <h4 id="judulGrafik">
                    Jumlah SPPG pada Setiap Desa
                </h4>

                <p id="subjudulGrafik">
                    Perbandingan jumlah SPPG berdasarkan desa
                </p>
            </div>

            <span class="chart-badge" id="chartBadge">
                Semua Desa
            </span>

        </div>

        <div class="chart-container">
            <canvas id="chartUtama"></canvas>
        </div>

        <div class="empty-chart d-none" id="emptyChart">
            <i class="bi bi-bar-chart"></i>

            <h5>Data belum tersedia</h5>

            <p>
                Tidak terdapat data untuk pilihan tersebut.
            </p>
        </div>

    </div>

    {{-- Tabel --}}
    <div class="table-card mt-4">

        <div class="table-card-header">

            <div>
                <h4>Data Statistik</h4>

                <p id="tableDescription">
                    Rincian data yang ditampilkan pada grafik
                </p>
            </div>

        </div>

        <div class="table-responsive">

            <table class="table statistic-table">

                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th id="tableNameHeading">Nama Desa</th>
                        <th id="tableValueHeading">Nilai</th>
                    </tr>
                </thead>

                <tbody id="statistikTableBody">
                    {{-- Diisi JavaScript --}}
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="2">Total</th>
                        <th id="statistikTotal">0</th>
                    </tr>   
                </tfoot>

            </table>

        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.4/jspdf.plugin.autotable.min.js"></script>
<script src="{{ asset('js/statistik-export.js') }}"></script>
<script>window.sppgData = @json($sppg);</script>
<script src="{{ asset('js/statistik.js') }}"></script>

@endsection