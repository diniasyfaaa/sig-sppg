@extends('layouts.admin')

@section('title', 'Ganti Password')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
@endsection

@section('content')

    <div class="dashboard-header mb-4">
        <div class="header-left">
            <h2>Ganti Password</h2>
            <p>Perbarui kata sandi akun Anda untuk keamanan sistem</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="back-link">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Alert Sukses / Error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="edit-container" style="margin-top: 0; max-width: 700px;">
        <div class="form-card">
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="current_password" class="form-label">Password Saat Ini <span
                            class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="current_password" id="current_password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            placeholder="Masukkan password Anda saat ini" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-key-fill"></i></span>
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter"
                            required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="text-muted">Password harus terdiri dari minimal 8 karakter.</small>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span
                            class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-check2-circle"></i></span>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                            placeholder="Ketik ulang password baru Anda" required>
                    </div>
                </div>

                <div class="form-actions pt-2 border-top d-flex gap-2">
                    <button type="submit" class="btn-add-sppg">
                        <i class="bi bi-shield-check me-1"></i> Simpan Password Baru
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary px-4 py-2"
                        style="border-radius: 10px;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection