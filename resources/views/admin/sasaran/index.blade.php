@extends('layouts.admin')

@section('title','Daftar Sasaran')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
    <link rel="stylesheet" href="{{ asset('css/detail-sppg.css') }}">
@endsection

@section('content')

<div class="text-end mb-0">

    <a href="{{ route('sppg.index') }}" class="back-link">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>

</div>
    <div class="dashboard-header">

    <div class="header-left">
        <h2 class="mb-0">{{ $sppg->nama_sppg }}</h2>
    </div>

</div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-toolbar">
    <a href="{{ route('sasaran.create', $sppg->id) }}" class="btn btn-main mb-4">
        <i class="bi bi-plus-circle"></i>
        Tambah Sasaran
    </a>
</div>

    <table class="table table-bordered table-striped">

        <thead class="table-light">
            <tr>
                <th width="60">No</th>
                <th width="160">Kode Sasaran</th>
                <th>Nama Sasaran</th>
                <th width="180">Kapasitas</th>
                <th width="180">Aksi</th>
            </tr>
        </thead>

        <tbody>

        @forelse($sasaran as $item)

            <tr>

                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kode ?? '-' }}</td>
                <td>{{ $item->nama_sasaran }}</td>
                <td>{{ $item->kapasitas }} Porsi/Hari</td>

                <td>
                    <div class="d-inline-flex align-items-center gap-1">
                        <a href="{{ route('sasaran.edit', $item->id) }}"
                            class="btn btn-sm btn-warning text-dark rounded-2 px-2.5 py-1 fw-normal">
                            Edit
                        </a>

                        <form action="{{ route('sasaran.destroy', $item->id) }}"
                            method="POST"
                            class="d-inline m-0">
                            @csrf
                            <button
                                type="submit"
                                class="btn btn-sm btn-danger rounded-2 px-2.5 py-1 fw-normal"
                                onclick="return confirm('Yakin ingin menghapus sasaran ini?')">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>

        @empty

            <tr>

                <td colspan="5" class="text-center">
                    Belum ada data sasaran
                </td>

            </tr>

        @endforelse

        </tbody>

        <tfoot>

            <tr>

                <th colspan="3" class="text-end">
                    Total Kapasitas
                </th>

                <th colspan="2">
                    {{ $sasaran->sum('kapasitas') }} Porsi/Hari
                </th>

            </tr>

        </tfoot>

    </table>

</div>
@endsection