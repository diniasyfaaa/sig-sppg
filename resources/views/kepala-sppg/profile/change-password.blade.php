@extends('layouts.kepala')

@section('title', 'Ganti Password')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">Ganti Password</h2>
            <p class="text-muted m-0">Perbarui kata sandi akun Anda untuk menjaga keamanan</p>
        </div>
        <a href="{{ route('kepala.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mx-auto" style="border-radius: 16px; max-width: 650px;">
        <div class="card-body p-4">
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="current_password" class="form-label fw-bold">Password Saat Ini <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                        <input
                            type="password"
                            name="current_password"
                            id="current_password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            placeholder="Masukkan password Anda saat ini"
                            required
                        >
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Password Baru <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-key-fill"></i></span>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Minimal 8 karakter"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="text-muted">Password harus terdiri dari minimal 8 karakter.</small>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-check2-circle"></i></span>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="form-control"
                            placeholder="Ketik ulang password baru Anda"
                            required
                        >
                    </div>
                </div>

                <div class="form-actions pt-3 border-top d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">
                        <i class="bi bi-shield-check me-1"></i> Simpan Password Baru
                    </button>
                    <a href="{{ route('kepala.dashboard') }}" class="btn btn-secondary px-4 py-2" style="border-radius: 10px;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
