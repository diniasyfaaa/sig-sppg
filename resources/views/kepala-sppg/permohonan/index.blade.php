@extends('layouts.kepala')

@section('title', 'Daftar Permohonan Saya')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">Daftar Permohonan Saya</h2>
            <p class="text-muted m-0">Riwayat permohonan perubahan data & penambahan sasaran yang Anda ajukan</p>
        </div>
        <div>
            <a href="{{ route('kepala.permohonan.create') }}" class="btn-main-sm fs-6 py-2 px-3">
                <i class="bi bi-plus-circle me-1"></i> Buat Permohonan Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Filter Status Cards (Dashboard Style) --}}
    <div class="dashboard-cards mb-4" style="grid-template-columns: repeat(4, 1fr);">
        <a href="{{ route('kepala.permohonan.index') }}" class="text-decoration-none">
            <div class="dashboard-card {{ empty($status) ? 'active-primary' : '' }}">
                <div class="icon-box blue">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
                <div>
                    <h5>Semua Permohonan</h5>
                    <h2>{{ $permohonanCount }}</h2>
                </div>
            </div>
        </a>

        <a href="{{ route('kepala.permohonan.index', ['status' => 'pending']) }}" class="text-decoration-none">
            <div class="dashboard-card {{ $status === 'pending' ? 'active-warning' : '' }}">
                <div class="icon-box orange">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <h5>Menunggu</h5>
                    <h2>{{ $pendingCount }}</h2>
                </div>
            </div>
        </a>

        <a href="{{ route('kepala.permohonan.index', ['status' => 'disetujui']) }}" class="text-decoration-none">
            <div class="dashboard-card {{ $status === 'disetujui' ? 'active-success' : '' }}">
                <div class="icon-box green">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <h5>Disetujui</h5>
                    <h2>{{ $approvedCount }}</h2>
                </div>
            </div>
        </a>

        <a href="{{ route('kepala.permohonan.index', ['status' => 'ditolak']) }}" class="text-decoration-none">
            <div class="dashboard-card {{ $status === 'ditolak' ? 'active-danger' : '' }}">
                <div class="icon-box red">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div>
                    <h5>Ditolak</h5>
                    <h2>{{ $rejectedCount }}</h2>
                </div>
            </div>
        </a>
    </div>

    {{-- Search Bar --}}
    <div class="table-toolbar mb-4">
        <form action="{{ route('kepala.permohonan.index') }}" method="GET" class="filter-wrapper">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif

            <div class="search-wrapper" style="width: 100%; min-width: 0;">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="search" class="search-input" value="{{ request('search') }}"
                    placeholder="Cari berdasarkan kode, jenis, atau usulan..." autocomplete="off">

                @if(request()->filled('search'))
                    <a href="{{ route('kepala.permohonan.index', array_filter(['status' => $status])) }}" class="search-clear"
                        title="Hapus pencarian">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif

                <button type="submit" class="btn-search">Cari</button>
            </div>
        </form>
    </div>

    {{-- Tabel Permohonan --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60">No</th>
                        <th width="150">Kode Permohonan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Jenis Permohonan</th>
                        <th>Usulan Perubahan / Data</th>
                        <th>Status</th>
                        <th class="text-end" width="120">Aksi</th>
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
                                <div class="text-dark">
                                    {{ $p->jenis === 'perubahan_data' ? 'Perubahan Data SPPG' : 'Perubahan Data Sasaran' }}
                                </div>
                            </td>
                            <td>
                                @if($p->jenis === 'perubahan_data')
                                    <div class="text-dark">{{ $p->field_perubahan }}</div>
                                    <small class="text-muted">Usulan: "{{ Str::limit($p->nilai_baru, 50) }}"</small>
                                @else
                                    <div class="text-dark">{{ $p->nama_sasaran }}</div>
                                    <small class="text-muted">Kapasitas: {{ $p->kapasitas_sasaran }} Orang</small>
                                @endif
                            </td>
                            <td>
                                @if($p->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-normal px-2.5 py-1">
                                        <i class="bi bi-clock me-1"></i> Menunggu Admin
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
                            <td class="text-end">
                                <a href="{{ route('kepala.permohonan.show', $p->id) }}" class="btn btn-sm btn-primary rounded-2 px-3 py-1 fw-normal">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-file-earmark-x fs-1 text-secondary d-block mb-2"></i>
                                <h5>Belum ada permohonan</h5>
                                <p class="small text-muted mb-3">Anda belum pernah mengajukan permohonan atau tidak ada data
                                    yang sesuai filter.</p>
                                <a href="{{ route('kepala.permohonan.create') }}" class="btn btn-primary btn-sm px-3">
                                    <i class="bi bi-plus-circle me-1"></i> Buat Permohonan Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($permohonan->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $permohonan->links() }}
            </div>
        @endif
    </div>

@endsection