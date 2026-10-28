@extends('layouts.admin')

@section('title', 'Edit Menu Harian - Admin')

@section('content')
<div class="container-fluid py-3">

    <div class="mb-4">
        <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Kelola Menu
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header text-white py-3 d-flex justify-content-between align-items-center" style="background-color: #0f2d49;">
            <h5 class="m-0 fw-bold">
                <i class="bi bi-pencil-square me-2"></i> Edit Menu Makanan (Admin)
            </h5>
            @if($menu->status == 'pending')
                <span class="badge bg-warning text-dark fw-semibold px-3 py-2" style="font-size: 0.8rem;">
                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Persetujuan
                </span>
            @elseif($menu->status == 'disetujui')
                <span class="badge bg-success fw-semibold px-3 py-2" style="font-size: 0.8rem;">
                    <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                </span>
            @elseif($menu->status == 'ditolak')
                <span class="badge bg-danger fw-semibold px-3 py-2" style="font-size: 0.8rem;">
                    <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                </span>
            @endif
        </div>
        <div class="card-body p-4">

            {{-- Banner for pending menus --}}
            @if($menu->status == 'pending')
                <div class="alert border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center gap-3" style="background-color: #fff8e6; border-left: 4px solid #f59e0b !important;">
                    <i class="bi bi-hourglass-split text-warning fs-4"></i>
                    <div>
                        <strong class="text-dark d-block">Permohonan Menu Harian Baru</strong>
                        <span class="text-secondary small">Menu ini diajukan oleh Kepala SPPG dan belum mendapatkan keputusan. Silakan <strong>Terima</strong> atau <strong>Tolak</strong> di bawah setelah ditinjau.</span>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="sppg_id" class="form-label fw-bold text-dark">Unit SPPG <span class="text-danger">*</span></label>
                        <select name="sppg_id" id="sppg_id" class="form-select @error('sppg_id') is-invalid @enderror" required>
                            @foreach($sppgs as $s)
                                <option value="{{ $s->id }}" {{ (old('sppg_id', $menu->sppg_id) == $s->id) ? 'selected' : '' }}>
                                    {{ $s->nama_sppg }} (Desa {{ $s->desa->nama_desa ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('sppg_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="siklus_ke" class="form-label fw-bold text-dark">Siklus Minggu Ke- <span class="text-danger">*</span></label>
                        <select name="siklus_ke" id="siklus_ke" class="form-select @error('siklus_ke') is-invalid @enderror" required>
                            @for($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}" {{ (old('siklus_ke', $menu->siklus_ke) == $i) ? 'selected' : '' }}>
                                    Minggu ke-{{ $i }}
                                </option>
                            @endfor
                        </select>
                        @error('siklus_ke')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="hari" class="form-label fw-bold text-dark">Hari <span class="text-danger">*</span></label>
                        <select name="hari" id="hari" class="form-select @error('hari') is-invalid @enderror" required>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                <option value="{{ $h }}" {{ (old('hari', $menu->hari) == $h) ? 'selected' : '' }}>
                                    {{ $h }}
                                </option>
                            @endforeach
                        </select>
                        @error('hari')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="menu_makanan" class="form-label fw-bold text-dark">Nama / Rincian Menu Makanan <span class="text-danger">*</span></label>
                        <input type="text" name="menu_makanan" id="menu_makanan" class="form-control @error('menu_makanan') is-invalid @enderror" value="{{ old('menu_makanan', $menu->menu_makanan) }}" required>
                        @error('menu_makanan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="standar_gizi" class="form-label fw-bold text-dark">Standar / Kandungan Gizi</label>
                        <textarea name="standar_gizi" id="standar_gizi" rows="3" class="form-control @error('standar_gizi') is-invalid @enderror">{{ old('standar_gizi', $menu->standar_gizi) }}</textarea>
                        @error('standar_gizi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="foto" class="form-label fw-bold text-dark">Foto Menu Makanan</label>
                        @if($menu->foto)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $menu->foto) }}" alt="Foto saat ini" class="rounded shadow-sm" style="max-height: 120px;">
                                <small class="text-muted d-block mt-1">Foto saat ini</small>
                            </div>
                        @endif
                        <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                        <small class="text-muted d-block mt-1">Biarkan kosong jika tidak ingin menganti foto.</small>
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <a href="{{ route('admin.menu.index') }}" class="btn btn-light rounded-3 px-4">Batal</a>

                    <div class="d-flex gap-2 flex-wrap">
                        {{-- Approve / Reject buttons (only if pending) --}}
                        @if($menu->status == 'pending')
                            {{-- Tombol Terima --}}
                            <form action="{{ route('admin.menu.approve', $menu->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success rounded-3 px-4 shadow-sm fw-semibold">
                                    <i class="bi bi-check-circle-fill me-1"></i> Terima
                                </button>
                            </form>

                            {{-- Tombol Tolak --}}
                            <button type="button" class="btn btn-danger rounded-3 px-4 shadow-sm fw-semibold"
                                data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle-fill me-1"></i> Tolak
                            </button>
                        @endif

                        {{-- Tombol Simpan Perubahan --}}
                        <button type="submit" class="btn text-white rounded-3 px-4 shadow-sm" style="background-color: #123957; border-color: #123957;">
                            <i class="bi bi-floppy-fill me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>

            {{-- Modal Tolak (hanya pending) --}}
            @if($menu->status == 'pending')
            <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title fw-bold">
                                <i class="bi bi-x-circle-fill me-2"></i> Konfirmasi Penolakan
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.menu.reject', $menu->id) }}" method="POST">
                            @csrf
                            <div class="modal-body p-4">
                                <p class="text-secondary mb-3">Tolak menu <strong>"{{ $menu->menu_makanan }}"</strong>? Berikan alasan yang jelas agar Kepala SPPG dapat memperbaikinya.</p>
                                <label for="alasan_penolakan" class="form-label fw-bold text-dark">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="alasan_penolakan" id="alasan_penolakan" rows="4"
                                    class="form-control @error('alasan_penolakan') is-invalid @enderror"
                                    placeholder="Tuliskan alasan penolakan secara jelas..." required>{{ old('alasan_penolakan') }}</textarea>
                                @error('alasan_penolakan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger rounded-3 fw-semibold">
                                    <i class="bi bi-x-circle-fill me-1"></i> Tolak Permohonan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
