@extends('layouts.admin')

@section('title', 'Kelola User')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
@endsection

@section('content')

    {{-- Header Halaman --}}
    <div class="dashboard-header">
        <div class="header-left">
            <h2>Kelola User</h2>
            <p>Manajemen Pengguna & Administrator Sistem</p>
        </div>
    </div>

    {{-- Alert Sukses / Error --}}
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

    {{-- Toolbar --}}
    <div class="table-toolbar">

        {{-- Tombol Tambah --}}
        <a href="{{ route('users.create') }}" class="btn-add-sppg">
            <i class="bi bi-person-plus-fill"></i>
            <span>Tambah User</span>
        </a>

        {{-- Form Pencarian --}}
        <form action="{{ route('users.index') }}" method="GET" class="filter-wrapper">
            <div class="search-wrapper" style="width: 100%; min-width: 0;">
                <i class="bi bi-search search-icon"></i>
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    value="{{ request('search') }}"
                    placeholder="Cari berdasarkan nama atau email..."
                    autocomplete="off"
                    aria-label="Cari data user"
                >

                @if(request()->filled('search'))
                    <a href="{{ route('users.index') }}" class="search-clear" title="Hapus pencarian">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif

                <button type="submit" class="btn-search" title="Cari data">
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel User --}}
    <div class="table-responsive sppg-table-wrapper">
        <table class="table table-bordered table-striped sppg-table">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th width="180">Kode</th>
                    <th>Nama User</th>
                    <th>Email</th>
                    <th>Role &amp; Unit</th>
                    <th>Terdaftar Pada</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $users->firstItem() + $loop->index }}</td>
                        <td>{{ $user->kode ?? '-' }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="admin-profile-avatar" style="width: 34px; height: 34px; font-size: 15px;">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <div>
                                    <span class="text-dark">{{ $user->name }}</span>
                                    @if(auth()->id() === $user->id)
                                        <small class="text-muted ms-1">(Saya)</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <div class="text-dark">
                                {{ $user->isKepalaSppg() ? 'Kepala SPPG' : 'Admin' }}
                            </div>
                            @if($user->isKepalaSppg() && $user->sppg)
                                <small class="text-muted d-block mt-0.5">{{ $user->sppg->nama_sppg }}</small>
                            @endif
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning text-dark rounded-2 px-2.5 py-1 fw-normal">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </a>

                                @if(auth()->id() !== $user->id)
                                    <form
                                        action="{{ route('users.destroy', $user->id) }}"
                                        method="POST"
                                        class="d-inline m-0"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun user {{ $user->name }}?')"
                                    >
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger rounded-2 px-2.5 py-1 fw-normal">
                                            <i class="bi bi-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-people"></i>
                                <h5>Belum ada user</h5>
                                <p>Tidak ditemukan data user yang sesuai dengan pencarian.</p>
                                <a href="{{ route('users.index') }}" class="btn-add-sppg">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    Tampilkan Semua User
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    @endif

@endsection
