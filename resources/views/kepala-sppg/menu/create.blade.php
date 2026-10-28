@extends('layouts.kepala')

@section('title', 'Tambah Menu Harian')

@section('content')
<div class="container-fluid py-3">

    <div class="mb-4">
        <a href="{{ route('kepala.menu.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Menu
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header text-white py-3" style="background-color: #0f2d49;">
            <h5 class="m-0 fw-bold">
                <i class="bi bi-plus-circle me-2"></i> Tambah Menu Makanan Harian
            </h5>
        </div>
        <div class="card-body p-4">

            <form action="{{ route('kepala.menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="siklus_ke" class="form-label fw-bold text-dark">Siklus Minggu Ke- <span class="text-danger">*</span></label>
                        <select name="siklus_ke" id="siklus_ke" class="form-select @error('siklus_ke') is-invalid @enderror" required>
                            <option value="">-- Pilih Siklus Minggu --</option>
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

                    <div class="col-md-6">
                        <label for="hari" class="form-label fw-bold text-dark">Hari <span class="text-danger">*</span></label>
                        <select name="hari" id="hari" class="form-select @error('hari') is-invalid @enderror" required>
                            <option value="">-- Pilih Hari --</option>
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
                        <input type="text" name="menu_makanan" id="menu_makanan" class="form-control @error('menu_makanan') is-invalid @enderror" placeholder="Contoh: Nasi Kuning, Ayam Goreng, Telur Dadar, Buah Pisang" value="{{ old('menu_makanan') }}" required>
                        @error('menu_makanan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="standar_gizi" class="form-label fw-bold text-dark">Standar / Kandungan Gizi</label>
                        <textarea name="standar_gizi" id="standar_gizi" rows="3" class="form-control @error('standar_gizi') is-invalid @enderror" placeholder="Contoh: Kalori: 450 kcal, Protein: 18g, Karbohidrat: 60g, Fat: 12g">{{ old('standar_gizi') }}</textarea>
                        @error('standar_gizi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="foto" class="form-label fw-bold text-dark">Foto Menu Makanan (Opsional)</label>
                        <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                        <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG. Maksimal 3 MB.</small>
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('kepala.menu.index') }}" class="btn btn-light rounded-3 px-4">Batal</a>
                    <button type="submit" class="btn text-white rounded-3 px-4 shadow-sm" style="background-color: #123957; border-color: #123957;">
                        <i class="bi bi-send-fill me-1"></i> Ajukan Menu Makanan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
