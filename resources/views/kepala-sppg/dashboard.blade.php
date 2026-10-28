@extends('layouts.kepala')

@section('title', 'Dashboard Kepala SPPG')

@section('content')

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Header Banner --}}
    <div class="dashboard-header d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="header-left">
            <h2>Selamat Datang, {{ $user->name }}!</h2>
            <p>
                @if($sppg)
                    Anda mengelola unit <strong>{{ $sppg->nama_sppg }}</strong> (Desa {{ $sppg->desa->nama_desa ?? '-' }}).
                @else
                    Akun Anda belum ditautkan ke unit SPPG. Silakan hubungi Administrator.
                @endif
            </p>
        </div>
    </div>

    @if($sppg)
        {{-- Card Info SPPG yang dikelola --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold m-0 text-dark">
                    <i class="bi bi-building text-primary me-2"></i> Data SPPG Anda Saat Ini
                </h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('user.sppg.show', $sppg->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                        <i class="bi bi-eye me-1"></i> Detail SPPG (Public)
                    </a>
                </div>
            </div>
            <div class="card-body p-4 border-top">
                <div class="row g-4">
                    <div class="col-md-3 col-sm-6">
                        <small class="text-muted d-block mb-1">Nama SPPG</small>
                        <strong class="fs-6 text-dark d-block">{{ $sppg->nama_sppg }}</strong>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <small class="text-muted d-block mb-1">Desa / Kelurahan</small>
                        <strong class="fs-6 text-dark d-block">{{ $sppg->desa->nama_desa ?? '-' }}</strong>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <small class="text-muted d-block mb-1">Ketua SPPG</small>
                        <strong class="fs-6 text-dark d-block">{{ $sppg->ketua_sppg ?? '-' }}</strong>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <small class="text-muted d-block mb-1">Status Operasional</small>
                        <span class="badge bg-{{ $sppg->badge_status }} px-3 py-2">
                            {{ $sppg->status }}
                        </span>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <small class="text-muted d-block mb-1">Tanggal Peresmian</small>
                        <span
                            class="text-dark fw-semibold">{{ $sppg->tanggal_peresmian ? \Carbon\Carbon::parse($sppg->tanggal_peresmian)->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <small class="text-muted d-block mb-1">Mitra / Yayasan</small>
                        <span class="text-dark fw-semibold">{{ $sppg->mitra_yayasan ?? '-' }}</span>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <small class="text-muted d-block mb-1">Jumlah Pegawai</small>
                        <span class="text-dark fw-semibold">{{ $sppg->jumlah_pegawai }} Orang</span>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <small class="text-muted d-block mb-1">Total Sasaran</small>
                        <span class="text-dark fw-semibold">{{ $sppg->sasaran->count() }} Sasaran</span>
                    </div>

                    <div class="col-12">
                        <small class="text-muted d-block mb-1">Alamat Lengkap</small>
                        <span class="text-dark">{{ $sppg->alamat ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Statistik Permohonan (Dashboard Cards Admin Style) --}}
    <div class="dashboard-cards mb-4" style="grid-template-columns: repeat(4, 1fr);">
        <div class="dashboard-card">
            <div class="icon-box blue">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
            <div>
                <h5>Total Permohonan</h5>
                <h2>{{ $permohonanCount }}</h2>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon-box orange">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <h5>Menunggu</h5>
                <h2>{{ $pendingCount }}</h2>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon-box green">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <h5>Disetujui</h5>
                <h2>{{ $approvedCount }}</h2>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon-box red">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <div>
                <h5>Ditolak</h5>
                <h2>{{ $rejectedCount }}</h2>
            </div>
        </div>
    </div>

    {{-- Permohonan Terbaru --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold m-0 text-dark">
                <i class="bi bi-clock-history text-primary me-2"></i> Permohonan Terakhir
            </h5>
            <a href="{{ route('kepala.permohonan.index') }}" class="btn btn-sm btn-outline-secondary">
                Lihat Semua Permohonan <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th width="150">Kode Permohonan</th>
                        <th>Tgl Pengajuan</th>
                        <th>Jenis Permohonan</th>
                        <th>Detail Permohonan</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPermohonan as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $p->kode ?? '-' }}
                            </td>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                {{ $p->jenis === 'perubahan_data' ? 'Perubahan Data SPPG' : 'Penambahan Sasaran' }}
                            </td>
                            <td>
                                @if($p->jenis === 'perubahan_data')
                                    <small class="d-block text-dark">Ubah: {{ $p->field_perubahan }}</small>
                                    <small class="text-muted">Nilai Baru: {{ Str::limit($p->nilai_baru, 40) }}</small>
                                @else
                                    <small class="d-block text-dark">Sasaran: {{ $p->nama_sasaran }}</small>
                                    <small class="text-muted">Kapasitas: {{ $p->kapasitas_sasaran }} orang</small>
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
                            <td class="text-end">
                                <a href="{{ route('kepala.permohonan.show', $p->id) }}" class="btn btn-sm btn-primary rounded-2 px-3 py-1 fw-normal">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                Belum ada permohonan yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection