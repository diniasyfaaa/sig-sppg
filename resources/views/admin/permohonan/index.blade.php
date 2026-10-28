@extends('layouts.admin')

@section('title', 'Kelola Permohonan Update SPPG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
@endsection

@section('content')

    {{-- Header --}}
    <div class="dashboard-header">
        <div class="header-left">
            <h2>Permohonan Update Data SPPG</h2>
            <p>Kelola pengajuan permohonan data dan sasaran dari Kepala SPPG</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Statistik Cards --}}
    <div class="dashboard-cards mb-4" style="grid-template-columns: repeat(4, 1fr);">
        <a href="{{ route('admin.permohonan.index') }}" class="text-decoration-none">
            <div class="dashboard-card {{ empty($status) ? 'active-primary' : '' }}">
                <div class="icon-box blue">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
                <div>
                    <h5>Semua Permohonan</h5>
                    <h2>{{ $totalCount }}</h2>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.permohonan.index', ['status' => 'pending']) }}" class="text-decoration-none">
            <div class="dashboard-card {{ $status === 'pending' ? 'active-warning' : '' }}">
                <div class="icon-box orange">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <h5>Menunggu Persetujuan</h5>
                    <h2>{{ $pendingCount }}</h2>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.permohonan.index', ['status' => 'disetujui']) }}" class="text-decoration-none">
            <div class="dashboard-card {{ $status === 'disetujui' ? 'active-success' : '' }}">
                <div class="icon-box green">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <h5>Telah Disetujui</h5>
                    <h2>{{ $approvedCount }}</h2>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.permohonan.index', ['status' => 'ditolak']) }}" class="text-decoration-none">
            <div class="dashboard-card {{ $status === 'ditolak' ? 'active-danger' : '' }}">
                <div class="icon-box red">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div>
                    <h5>Telah Ditolak</h5>
                    <h2>{{ $rejectedCount }}</h2>
                </div>
            </div>
        </a>
    </div>

    {{-- Toolbar & Filter --}}
    <div class="table-toolbar">

        <form action="{{ route('admin.permohonan.index') }}" method="GET" class="filter-wrapper">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div class="search-wrapper" style="width: 100%; min-width: 0;">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="search" class="search-input" value="{{ request('search') }}"
                    placeholder="Cari berdasarkan SPPG atau pemohon..." autocomplete="off">
                @if(request()->filled('search'))
                    <a href="{{ route('admin.permohonan.index', array_filter(['status' => $status])) }}" class="search-clear"
                        title="Hapus pencarian">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
                <button type="submit" class="btn-search">Cari</button>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-responsive sppg-table-wrapper">
        <table class="table table-bordered table-striped sppg-table align-middle">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th width="150">Kode Permohonan</th>
                    <th>Tgl Pengajuan</th>
                    <th>Pemohon</th>
                    <th>Unit SPPG</th>
                    <th>Jenis & Detail Usulan</th>
                    <th>Status</th>
                    <th width="140" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permohonan as $p)
                    <tr>
                        <td>{{ $permohonan->firstItem() + $loop->index }}</td>
                        <td>
                            {{ $p->kode ?? '-' }}
                        </td>
                        <td>
                            <div>{{ $p->created_at->format('d/m/Y') }}</div>
                            <small class="text-muted d-block">{{ $p->created_at->format('H:i') }} WIB</small>
                        </td>
                        <td>
                            <div class="text-dark">{{ $p->user->name ?? 'User Dihapus' }}</div>
                            <small class="text-muted">{{ $p->user->email ?? '-' }}</small>
                        </td>
                        <td>
                            <div class="text-dark">{{ $p->sppg->nama_sppg ?? '-' }}</div>
                            <div class="mt-1">
                                <small class="text-secondary">({{ $p->sppg->kode ?? '-' }})</small>
                                <small class="text-muted d-block mt-0.5">Desa {{ $p->sppg->desa->nama_desa ?? '-' }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="text-dark">
                                {{ $p->jenis === 'perubahan_data' ? 'Perubahan Data' : 'Perubahan Data Sasaran' }}
                            </div>
                            @if($p->jenis === 'perubahan_data')
                                <div class="small text-muted mt-0.5">Ubah: {{ $p->field_perubahan }}</div>
                                <small class="text-muted">Usulan: "{{ Str::limit($p->nilai_baru, 40) }}"</small>
                            @else
                                <div class="small text-muted mt-0.5">Sasaran: {{ $p->nama_sasaran }}</div>
                                <small class="text-muted">Kapasitas: {{ $p->kapasitas_sasaran }} Orang</small>
                            @endif
                        </td>
                        <td>
                            @if($p->status === 'pending')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-normal px-2.5 py-1">
                                    <i class="bi bi-clock me-1"></i> Pending
                                </span>
                            @elseif($p->status === 'disetujui')
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-normal px-2.5 py-1">
                                    <i class="bi bi-check-circle me-1"></i> Disetujui
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-normal px-2.5 py-1">
                                    <i class="bi bi-x-circle me-1"></i> Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.permohonan.show', $p->id) }}" class="btn btn-sm btn-primary rounded-2 px-3 py-1 fw-normal">
                                <i class="bi bi-eye me-1"></i> Tinjau
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-inbox fs-1 text-secondary"></i>
                                <h5>Belum Ada Permohonan</h5>
                                <p class="text-muted">Tidak ditemukan permohonan update SPPG yang sesuai.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($permohonan->hasPages())
        <div class="mt-4">
            {{ $permohonan->links() }}
        </div>
    @endif

@endsection