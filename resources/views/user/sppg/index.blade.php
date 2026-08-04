@extends('layouts.user')

@section('title', 'Daftar SPPG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
@endsection

@section('content')

<div class="user-page-container">

    {{-- Header halaman --}}
    <div class="dashboard-header">
        <div class="header-left">
            <h2>Data SPPG</h2>
            <p>Data SPPG Kecamatan Cileunyi</p>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="table-toolbar">

        {{-- Form filter dan pencarian --}}
        <form
            id="filterForm"
            action="{{ route('user.sppg.index') }}"
            method="GET"
            class="filter-wrapper"
        >
            {{-- Filter desa --}}
            <select
                name="desa"
                class="filter-select"
                onchange="document.getElementById('filterForm').submit()"
                aria-label="Filter desa"
            >
                <option value="">Semua Desa</option>

                @foreach($desa as $d)
                    <option
                        value="{{ $d->id }}"
                        {{ request('desa') == $d->id ? 'selected' : '' }}
                    >
                        {{ $d->nama_desa }}
                    </option>
                @endforeach
            </select>

            {{-- Search bar --}}
            <div class="search-wrapper">
                <i class="bi bi-search search-icon"></i>

                <input
                    type="text"
                    name="search"
                    class="search-input"
                    value="{{ request('search') }}"
                    placeholder="Cari SPPG, ketua, yayasan, sasaran..."
                    autocomplete="off"
                    aria-label="Cari data SPPG"
                >

                @if(request()->filled('search'))
                    <a
                        href="{{ route('user.sppg.index', ['desa' => request('desa')]) }}"
                        class="search-clear"
                        title="Hapus pencarian"
                    >
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif

                <button
                    type="submit"
                    class="btn-search"
                    title="Cari data"
                >
                    Cari
                </button>
            </div>

            {{-- Reset semua filter --}}
            @if(request()->filled('desa') || request()->filled('search'))
                <a
                    href="{{ route('user.sppg.index') }}"
                    class="btn-reset-filter"
                    title="Hapus semua filter"
                >
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
                            <span class="sppg-name">
                                {{ $item->nama_sppg }}
                            </span>
                        </td>

                        <td>
                            {{ optional($item->desa)->nama_desa ?? '-' }}
                        </td>

                        <td>
                            @if($item->status === 'Aktif')
                                <span class="badge bg-success">
                                    Aktif
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="action-buttons">
                                <a
                                    href="{{ route('user.sppg.show', $item->id) }}"
                                    class="btn btn-info btn-sm"
                                >
                                    Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="bi bi-search"></i>

                                <h5>Data tidak ditemukan</h5>

                                <p>
                                    Tidak ada data SPPG yang sesuai dengan filter
                                    atau kata pencarian.
                                </p>
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
</div>
@endsection