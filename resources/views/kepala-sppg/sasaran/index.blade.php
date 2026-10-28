@extends('layouts.kepala')

@section('title', 'Daftar Sasaran')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">{{ $sppg->nama_sppg ?? 'Daftar Sasaran' }}</h2>
            <p class="text-muted m-0">Data sasaran penerima manfaat SPPG</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('kepala.permohonan.create', ['jenis' => 'penambahan_sasaran']) }}"
                class="btn-main-sm fs-6 py-2 px-3">
                <i class="bi bi-pencil-square me-1"></i> Ajukan Perubahan Data Sasaran
            </a>

        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Toolbar Filter & Cari --}}
    <div class="table-toolbar mb-4">
        <form action="{{ route('kepala.sasaran.index') }}" method="GET" class="filter-wrapper" id="searchForm">
            <div class="search-wrapper" style="width: 100%; min-width: 0;">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="search" id="sasaranSearchInput" class="search-input"
                    value="{{ request('search') }}" placeholder="Cari nama sasaran..." autocomplete="off">
                @if(request()->filled('search'))
                    <a href="{{ route('kepala.sasaran.index') }}" class="search-clear" title="Hapus pencarian">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
                <button type="submit" class="btn-search" title="Cari data">Cari</button>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0" id="sasaranTable">
                    <thead class="table-light">
                        <tr>
                            <th width="60" class="text-center">No</th>
                            <th width="160">Kode Sasaran</th>
                            <th>Nama Sasaran</th>
                            <th width="200" class="text-end pe-4">Kapasitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sasaran as $item)
                            <tr class="sasaran-row">
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>{{ $item->kode ?? '-' }}</td>
                                <td class="sasaran-nama">{{ $item->nama_sasaran }}</td>
                                <td class="text-end pe-4">{{ $item->kapasitas }} Porsi/Hari</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    Belum ada data sasaran
                                    {{ request('search') ? 'yang sesuai pencarian "' . request('search') . '"' : '' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end pe-3 fw-bold">
                                Total Kapasitas
                            </th>
                            <th class="text-end pe-4 fw-bold">
                                {{ $sasaran->sum('kapasitas') }} Porsi/Hari
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.getElementById('sasaranSearchInput')?.addEventListener('input', function (e) {
            const query = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.sasaran-row');
            rows.forEach(row => {
                const text = row.querySelector('.sasaran-nama')?.textContent.toLowerCase() || '';
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    </script>
@endsection