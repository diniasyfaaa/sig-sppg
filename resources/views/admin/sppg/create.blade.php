@extends('layouts.admin')
@section('title', 'Tambah SPPG')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/detail-sppg.css') }}">
@endsection
@section('content')

<div class="text-end mb-3">

    <a href="javascript:history.back()" class="back-link">
    Kembali
</a>

</div>

<div class="dashboard-header">

    <div class="header-left">
        <h2>Tambah Data SPPG</h2>
    </div>

</div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif

    <form action="{{ route('sppg.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">

        <div class="col-md-6 mb-3">
            <label>Nama SPPG</label>
            <input type="text" name="nama_sppg" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>Desa</label>

            <select name="desa_id" class="form-control" required>
                <option value="">-- Pilih Desa --</option>
                @foreach($desa as $d)
                    <option value="{{ $d->id }}">
                        {{ $d->nama_desa }}
                    </option>
                @endforeach

            </select>

        </div>

        <div class="col-md-12 mb-3">
            <label>Alamat Lengkap</label>
            <textarea name="alamat" class="form-control" rows="3"></textarea>
        </div>

        <div class="col-md-6 mb-3">
            <label>Tanggal Peresmian</label>

            <input
                type="text"
                id="tanggal_peresmian"
                name="tanggal_peresmian"
                class="form-control"
                value="{{ old('tanggal_peresmian', $sppg->tanggal_peresmian ?? '') }}"
                placeholder="dd/mm/yyyy"
                autocomplete="off">
        </div>

        <div class="col-md-6 mb-3">
            <label>Status</label>

            <select name="status" class="form-control">

                <option value="Aktif">Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>

            </select>

        </div>

        <div class="col-md-6 mb-3">
            <label>Ketua SPPG</label>
            <input type="text" name="ketua_sppg" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label>Mitra / Yayasan</label>
            <input type="text" name="mitra_yayasan" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label>Jumlah Pegawai</label>
            <input type="number" name="jumlah_pegawai" class="form-control">
        </div>

        <div class="col-md-12 mb-3">
            <label>Link Google Maps</label>
            <input type="text" name="link_google_maps" class="form-control">
        </div>

        <div class="col-md-4 mb-3">

    <label class="form-label">
        Foto Depan
    </label>

    <input
        type="file"
        name="foto_depan"
        class="form-control"
        accept=".jpg,.jpeg,.png,image/jpeg,image/png"
    >

    <small class="text-muted">
        Format JPG, JPEG, atau PNG. Maksimal 2 MB.
    </small>

</div>

<div class="col-md-4 mb-3">

    <label class="form-label">
        Foto Dapur
    </label>

    <input
        type="file"
        name="foto_dapur"
        class="form-control"
        accept=".jpg,.jpeg,.png,image/jpeg,image/png"
    >

    <small class="text-muted">
        Format JPG, JPEG, atau PNG. Maksimal 2 MB.
    </small>

</div>

<div class="col-md-4 mb-3">

    <label class="form-label">
        Foto Belakang
    </label>

    <input
        type="file"
        name="foto_belakang"
        class="form-control"
        accept=".jpg,.jpeg,.png,image/jpeg,image/png"
    >

    <small class="text-muted">
        Format JPG, JPEG, atau PNG. Maksimal 2 MB.
    </small>

</div>
</div>

    <button class="btn btn-main mb-4">
        Simpan Data
    </button>

</form>
@endsection

@section('scripts')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr('#tanggal_peresmian', {
                allowInput: true,
                clickOpens: true,
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd/m/Y',
                disableMobile: true
            });
        });

@endsection