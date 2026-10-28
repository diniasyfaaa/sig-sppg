@extends('layouts.admin')

@section('title', 'Tambah User Baru')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
@endsection

@section('content')

    <div class="dashboard-header mb-4">
        <div class="header-left">
            <h2>Tambah User Baru</h2>
            <p>Buat akun pengelola / admin / Kepala SPPG baru untuk sistem</p>
        </div>
        <div>
            <a href="{{ route('users.index') }}" class="back-link">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="edit-container" style="margin-top: 0;">
        <div class="form-card">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Contoh: Admin Cileunyi / Ahmad Sudrajat"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="Contoh: user@cileunyi.go.id"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label">Role Akses <span class="text-danger">*</span></label>
                        <select
                            name="role"
                            id="role"
                            class="form-select @error('role') is-invalid @enderror"
                            required
                        >
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="kepala_sppg" {{ old('role') === 'kepala_sppg' ? 'selected' : '' }}>Kepala SPPG</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6" id="sppgContainer" style="{{ old('role') === 'kepala_sppg' ? '' : 'display: none;' }}">
                        <label for="sppg_id" class="form-label">Pilih SPPG <span class="text-danger">*</span></label>
                        <select
                            name="sppg_id"
                            id="sppg_id"
                            class="form-select @error('sppg_id') is-invalid @enderror"
                        >
                            <option value="">-- Pilih Unit SPPG --</option>
                            @foreach($sppgs as $sppg)
                                <option value="{{ $sppg->id }}" {{ old('sppg_id') == $sppg->id ? 'selected' : '' }}>
                                    {{ $sppg->nama_sppg }} (Desa {{ $sppg->desa->nama_desa ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('sppg_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
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

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="form-control"
                            placeholder="Ketik ulang password"
                            required
                        >
                    </div>
                </div>

                <div class="form-actions mt-4 pt-2 border-top d-flex gap-2">
                    <button type="submit" class="btn-add-sppg">
                        <i class="bi bi-check-circle me-1"></i> Simpan User
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary px-4 py-2" style="border-radius: 10px;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('role');
        const sppgContainer = document.getElementById('sppgContainer');
        const sppgSelect = document.getElementById('sppg_id');

        function toggleSppgSelect() {
            if (roleSelect.value === 'kepala_sppg') {
                sppgContainer.style.display = 'block';
                sppgSelect.required = true;
            } else {
                sppgContainer.style.display = 'none';
                sppgSelect.required = false;
                sppgSelect.value = '';
            }
        }

        roleSelect.addEventListener('change', toggleSppgSelect);
        toggleSppgSelect();
    });
</script>
@endsection
