@extends('layouts.admin')

@section('title','Edit Sasaran')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
@endsection

@section('content')

<div class="text-end mb-3">

    <a href="javascript:history.back()" class="back-link">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>

</div>

<div class="dashboard-header">

    <div class="header-left">
        <h2>Edit Sasaran SPPG</h2>
    </div>
</div>

    <form action="{{ route('sasaran.update', $sasaran->id) }}" method="POST">

        @csrf

        <div class="mb-3">

            <label class="form-label">
                Nama Sasaran
            </label>

            <input
                type="text"
                name="nama_sasaran"
                class="form-control"
                value="{{ $sasaran->nama_sasaran }}"
                required>

        </div>

        <div class="mb-3">

            <label class="form-label">
                Kapasitas
            </label>

            <input
                type="number"
                name="kapasitas"
                class="form-control"
                value="{{ $sasaran->kapasitas }}"
                required>

        </div>

        <button class="btn btn-warning mb-4">
            <i class="bi bi-floppy-fill me-2"></i>
            Update Data
        </button>

    </form>

</div>

@endsection