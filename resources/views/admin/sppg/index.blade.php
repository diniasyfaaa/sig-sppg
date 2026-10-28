@extends('layouts.admin')

@section('title', 'Daftar SPPG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
@endsection

@section('content')

    {{-- Header halaman --}}
    <div class="dashboard-header">
        <div class="header-left">
            <h2>Data SPPG</h2>
            <p>Data SPPG Kecamatan Cileunyi</p>
        </div>
    </div>

    {{-- Pesan berhasil --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="table-toolbar">

        {{-- Tombol tambah --}}
        <a href="{{ route('sppg.create') }}" class="btn-add-sppg">
            <i class="bi bi-plus-circle"></i>
            <span>Tambah SPPG</span>
        </a>

        {{-- Form filter dan pencarian --}}
        <form id="filterForm" action="{{ route('sppg.index') }}" method="GET" class="filter-wrapper">
            {{-- Filter desa --}}
            <select name="desa" class="filter-select" onchange="document.getElementById('filterForm').submit()"
                aria-label="Filter desa">
                <option value="">Semua Desa</option>

                @foreach($desa as $d)
                    <option value="{{ $d->id }}" {{ request('desa') == $d->id ? 'selected' : '' }}>
                        {{ $d->nama_desa }}
                    </option>
                @endforeach
            </select>

            {{-- Search bar --}}
            <div class="search-wrapper">
                <i class="bi bi-search search-icon"></i>

                <input type="text" name="search" class="search-input" value="{{ request('search') }}"
                    placeholder="Cari SPPG, ketua, yayasan, sasaran..." autocomplete="off" aria-label="Cari data SPPG">

                @if(request()->filled('search'))
                    <a href="{{ route('sppg.index', ['desa' => request('desa')]) }}" class="search-clear"
                        title="Hapus pencarian">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif

                <button type="submit" class="btn-search" title="Cari data">
                    Cari
                </button>
            </div>

            @if(request()->filled('desa') || request()->filled('search'))
                <a href="{{ route('sppg.index') }}" class="btn-reset-filter" title="Hapus semua filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Tabel data --}}
    <div class="table-responsive sppg-table-wrapper">
        <table class="table table-bordered table-striped sppg-table">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th width="140">Kode SPPG</th>
                    <th>Nama SPPG</th>
                    <th>Desa</th>
                    <th width="130">Status</th>
                    <th width="230">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($sppg as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{ $item->kode ?? '-' }}
                        </td>

                        <td>
                            <span class="sppg-name">
                                {{ $item->nama_sppg }}
                            </span>
                        </td>

                        <td>
                            {{ optional($item->desa)->nama_desa ?? '-' }}
                        </td>

                        <td>
                            @if($item->status === 'Aktif')
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-normal px-2.5 py-1">
                                    Aktif
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-normal px-2.5 py-1">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('sppg.show', $item->id) }}" class="btn btn-sm btn-primary rounded-2 px-2.5 py-1 fw-normal">
                                    Detail
                                </a>

                                <a href="{{ route('sppg.edit', $item->id) }}" class="btn btn-sm btn-warning text-dark rounded-2 px-2.5 py-1 fw-normal">
                                    Edit
                                </a>

                                <form action="{{ route('sppg.destroy', $item->id) }}" method="POST" class="d-inline m-0"
                                    onsubmit="return confirm('Yakin ingin menghapus data SPPG ini? Semua data sasaran yang berkaitan juga akan ikut terhapus.')">
                                    @csrf

                                    <button type="submit" class="btn btn-sm btn-danger rounded-2 px-2.5 py-1 fw-normal">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-search"></i>

                                <h5>Data tidak ditemukan</h5>

                                <p>
                                    Tidak ada data SPPG yang sesuai dengan filter
                                    atau kata pencarian.
                                </p>

                                <a href="{{ route('sppg.index') }}" class="btn-add-sppg">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    Tampilkan Semua Data
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection