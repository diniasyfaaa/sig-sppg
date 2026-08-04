@extends('layouts.admin')

@section('title','Daftar Sasaran')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
    <link rel="stylesheet" href="{{ asset('css/detail-sppg.css') }}">
@endsection

@section('content')

<div class="text-end mb-0">

    <a href="{{ route('sppg.index') }}" class="back-link">
    Kembali
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
                <th>Nama Sasaran</th>
                <th width="180">Kapasitas</th>
                <th width="180">Aksi</th>
            </tr>
        </thead>

        <tbody>

        @forelse($sasaran as $item)

            <tr>

                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_sasaran }}</td>
                <td>{{ $item->kapasitas }} Porsi/Hari</td>

                <td>

                <a href="{{ route('sasaran.edit', $item->id) }}"
                class="btn btn-warning btn-sm">
                    Edit
                </a>

                <form action="{{ route('sasaran.destroy', $item->id) }}"
                    method="POST"
                    style="display:inline;">

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin ingin menghapus sasaran ini?')">
                        Hapus
                    </button>
                </form>
            </td>
            </tr>

        @empty

            <tr>

                <td colspan="4" class="text-center">
                    Belum ada data sasaran
                </td>

            </tr>

        @endforelse

        </tbody>

        <tfoot>

            <tr>

                <th colspan="2" class="text-end">
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