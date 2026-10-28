@extends('layouts.admin')

@section('title', 'Kelola Menu Makanan Harian - Admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
@endsection

@section('content')
<div class="container-fluid py-3">

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" data-bs-dismiss="alert" class="btn-close" aria-label="Close"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark m-0">
                <i class="bi bi-journal-richtext text-primary me-2"></i> Kelola Menu Makanan Harian
            </h3>
            <p class="text-muted m-0 small mt-1">
                Verifikasi dan kelola menu harian siklus mingguan dari seluruh unit SPPG.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.menu.create', ['sppg_id' => $sppgIdFilter]) }}" class="btn text-white rounded-3 shadow-sm px-3 py-2" style="background-color: #123957; border-color: #123957;">
                <i class="bi bi-plus-lg me-1"></i> Tambah Menu Harian (Direct Admin)
            </a>
        </div>
    </div>

    {{-- Toolbar Filter & Search --}}
    <div class="table-toolbar mb-4">
        <form action="{{ route('admin.menu.index') }}" method="GET" class="filter-wrapper">
            <select name="sppg_id" class="filter-select" onchange="this.form.submit()" style="width: auto; min-width: 200px;">
                <option value="">Semua Unit SPPG</option>
                @foreach($sppgs as $s)
                    <option value="{{ $s->id }}" {{ $sppgIdFilter == $s->id ? 'selected' : '' }}>
                        {{ $s->nama_sppg }} (Desa {{ $s->desa->nama_desa ?? '-' }})
                    </option>
                @endforeach
            </select>

            <select name="status" class="filter-select" onchange="this.form.submit()" style="width: auto; min-width: 160px;">
                <option value="">Semua Status</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending ({{ $pendingCount }})</option>
                <option value="disetujui" {{ $status == 'disetujui' ? 'selected' : '' }}>Disetujui ({{ $approvedCount }})</option>
                <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>

            <div class="search-wrapper" style="width: 100%; min-width: 280px;">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="search" class="search-input" placeholder="Cari nama menu atau SPPG..." value="{{ $search }}" autocomplete="off">
                @if(!empty($search))
                    <a href="{{ route('admin.menu.index', array_filter(['sppg_id' => $sppgIdFilter, 'status' => $status])) }}" class="search-clear" title="Hapus pencarian">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
                <button type="submit" class="btn-search" title="Cari data">Cari</button>
            </div>

            @if($sppgIdFilter || $status || $search)
                <a href="{{ route('admin.menu.index') }}" class="btn-reset-filter" title="Reset semua filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 60px;">No</th>
                        <th>Unit SPPG</th>
                        <th>Siklus & Hari</th>
                        <th>Menu Makanan</th>
                        <th>Standar Gizi</th>
                        <th>Foto</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Aksi / Persetujuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $item)
                        <tr class="{{ $item->status == 'pending' ? 'table-warning' : '' }}">
                            <td class="ps-3">
                                {{ $menus->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <div class="text-dark">{{ $item->sppg->nama_sppg ?? '-' }}</div>
                                <small class="text-muted">Desa {{ $item->sppg->desa->nama_desa ?? '-' }}</small>
                            </td>
                            <td>
                                <div>Minggu ke-{{ $item->siklus_ke }}</div>
                                <small class="text-muted">{{ $item->hari }}</small>
                            </td>
                            <td>
                                <span class="text-dark">{{ $item->menu_makanan }}</span>
                                @if($item->status == 'pending')
                                    <br><span class="badge bg-warning text-dark mt-1" style="font-size: 0.7rem;"><i class="bi bi-hourglass-split me-1"></i> Perlu Ditinjau</span>
                                @endif
                            </td>
                            <td>
                                @if($item->standar_gizi)
                                    <span class="small text-secondary">{{ Str::limit($item->standar_gizi, 70) }}</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->foto)
                                    <a href="{{ asset('storage/' . $item->foto) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Menu" class="rounded shadow-sm" style="width: 48px; height: 48px; object-fit: cover;">
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->status == 'disetujui')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle fw-normal px-2.5 py-1">
                                        <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                    </span>
                                @elseif($item->status == 'pending')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-normal px-2.5 py-1">
                                        <i class="bi bi-clock-fill me-1"></i> Pending Approval
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-normal px-2.5 py-1">
                                        <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex align-items-center justify-content-end gap-1 flex-wrap">
                                    @if($item->status == 'pending')
                                        <form action="{{ route('admin.menu.approve', $item->id) }}" method="POST" class="d-inline m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-2 px-2.5 py-1 fw-normal" title="Setujui (Tampilkan Publik)">
                                                <i class="bi bi-check-lg me-1"></i> Setujui
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-warning text-dark rounded-2 px-2.5 py-1 fw-normal" title="Tolak Permohonan" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}">
                                            <i class="bi bi-x-lg me-1"></i> Tolak
                                        </button>
                                    @endif

                                    <a href="{{ route('admin.menu.edit', $item->id) }}" class="btn btn-sm btn-primary rounded-2 px-2.5 py-1 fw-normal" title="Edit Admin">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <button type="button" class="btn btn-sm btn-danger rounded-2 px-2.5 py-1 fw-normal" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteAdminModal{{ $item->id }}">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>

                                {{-- Reject Modal --}}
                                @if($item->status == 'pending')
                                    <div class="modal fade text-start" id="rejectModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <form action="{{ route('admin.menu.reject', $item->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-warning text-dark">
                                                        <h5 class="modal-title fw-bold">
                                                            <i class="bi bi-exclamation-octagon-fill me-2"></i> Tolak Permohonan Menu
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <p class="small text-muted mb-2">
                                                            Menu: <strong>{{ $item->menu_makanan }}</strong> ({{ $item->sppg->nama_sppg ?? '' }})
                                                        </p>
                                                        <label for="alasan_penolakan" class="form-label fw-bold text-dark">Alasan Penolakan <span class="text-danger">*</span></label>
                                                        <textarea name="alasan_penolakan" id="alasan_penolakan" rows="3" class="form-control" placeholder="Tuliskan catatan/alasan penolakan..." required></textarea>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger rounded-3">
                                                            <i class="bi bi-x-circle-fill me-1"></i> Kirim Penolakan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Delete Modal --}}
                                <div class="modal fade text-start" id="deleteAdminModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title fw-bold">
                                                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Hapus Menu Harian
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <p class="m-0 text-dark">
                                                    Yakin hapus menu <strong>"{{ $item->menu_makanan }}"</strong> milik SPPG {{ $item->sppg->nama_sppg ?? '' }}?
                                                </p>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin.menu.destroy', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger rounded-3">
                                                        <i class="bi bi-trash-fill me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Tidak ada data menu harian yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($menus->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $menus->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
