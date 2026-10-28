@extends('layouts.admin')

@section('title', 'Tambah Menu Harian - Admin')

@section('content')
<div class="container-fluid py-3">

    <div class="mb-4">
        <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Kelola Menu
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header text-white py-3" style="background-color: #0f2d49;">
            <h5 class="m-0 fw-bold">
                <i class="bi bi-plus-circle me-2"></i> Tambah Menu Makanan (Direct Admin)
            </h5>
        </div>
        <div class="card-body p-4">

            <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="sppg_id" class="form-label fw-bold text-dark">Pilih Unit SPPG <span class="text-danger">*</span></label>
                        <select name="sppg_id" id="sppg_id" class="form-select @error('sppg_id') is-invalid @enderror" required>
                            <option value="">-- Pilih SPPG --</option>
                            @foreach($sppgs as $s)
                                <option value="{{ $s->id }}" {{ old('sppg_id', $sppg_id) == $s->id ? 'selected' : '' }}>
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
                                <option value="{{ $i }}" {{ old('siklus_ke') == $i ? 'selected' : '' }}>
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
                                <option value="{{ $h }}" {{ old('hari') == $h ? 'selected' : '' }}>
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
                        <input type="text" name="menu_makanan" id="menu_makanan" class="form-control @error('menu_makanan') is-invalid @enderror" value="{{ old('menu_makanan') }}" placeholder="Contoh: Nasi Goreng, Telur Mata Sapi, Kerupuk, Susu" required>
                        @error('menu_makanan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="standar_gizi" class="form-label fw-bold text-dark">Standar / Kandungan Gizi</label>
                        <textarea name="standar_gizi" id="standar_gizi" rows="3" class="form-control @error('standar_gizi') is-invalid @enderror" placeholder="Contoh: Kalori: 500 kcal, Protein: 20g, Karbohidrat: 70g">{{ old('standar_gizi') }}</textarea>
                        @error('standar_gizi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="foto" class="form-label fw-bold text-dark">Foto Menu Makanan (Opsional)</label>
                        <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                        <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG. Maks 3 MB.</small>
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.menu.index') }}" class="btn btn-light rounded-3 px-4">Batal</a>
                    <button type="submit" class="btn text-white rounded-3 px-4 shadow-sm" style="background-color: #123957; border-color: #123957;">
                        <i class="bi bi-check-circle-fill me-1"></i> Simpan & Dipublikasikan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
