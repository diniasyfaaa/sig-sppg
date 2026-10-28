@extends('layouts.admin')

@section('title', 'Tambah Sasaran')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/detail-sppg.css') }}">
@endsection

@section('content')

<div class="text-end mb-3">
    <a href="javascript:history.back()" class="back-link">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="dashboard-header">
    <div class="header-left">
        <h2 class="mb-0">{{ $sppg->nama_sppg }}</h2>
        <p>Tambah Data Sasaran SPPG</p>
    </div>
</div>

{{-- Pesan error umum --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('sasaran.store', $sppg->id) }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="nama_sasaran" class="form-label">
            Nama Sasaran
        </label>

        <input
            type="text"
            name="nama_sasaran"
            id="nama_sasaran"
            class="form-control @error('nama_sasaran') is-invalid @enderror"
            placeholder="Contoh: SDN Cimekar 1"
            value="{{ old('nama_sasaran') }}"
            required
        >

        @error('nama_sasaran')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="kapasitas" class="form-label">
            Kapasitas
        </label>

        <input
            type="number"
            name="kapasitas"
            id="kapasitas"
            class="form-control @error('kapasitas') is-invalid @enderror"
            placeholder="Contoh: 120"
            value="{{ old('kapasitas') }}"
            min="0"
            required
        >

        @error('kapasitas')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <button class="btn btn-main mb-4">
        Simpan Data
    </button>

</form>

@endsection