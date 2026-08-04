@extends('layouts.admin')

@section('title', 'Edit SPPG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
@endsection

@section('content')


<div class="text-end mb-0">

    <a href="javascript:history.back()" class="back-link">
    Kembali
</a>

</div>

<div class="dashboard-header">

    <div class="header-left">
        <h2>Edit SPPG</h2>
    </div>

</div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('sppg.update', $sppg->id) }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Nama SPPG</label>
                <input
                    type="text"
                    name="nama_sppg"
                    class="form-control"
                    value="{{ $sppg->nama_sppg }}"
                    required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Desa</label>

                <select name="desa_id" class="form-control" required>

                    @foreach($desa as $d)

                        <option value="{{ $d->id }}"
                            {{ $sppg->desa_id == $d->id ? 'selected' : '' }}>

                            {{ $d->nama_desa }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-12 mb-3">
                <label>Alamat Lengkap</label>

                <textarea
                    name="alamat"
                    class="form-control"
                    rows="3">{{ $sppg->alamat }}</textarea>

            </div>

            <div class="col-md-6 mb-3">
                <label>Tanggal Peresmian</label>

                <input
                    type="date"
                    name="tanggal_peresmian"
                    class="form-control"
                    value="{{ $sppg->tanggal_peresmian }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Status</label>

                <select name="status" class="form-control">

                    <option value="Aktif"
                        {{ $sppg->status == 'Aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="Tidak Aktif"
                        {{ $sppg->status == 'Tidak Aktif' ? 'selected' : '' }}>
                        Tidak Aktif
                    </option>

                </select>

            </div>

            <div class="col-md-6 mb-3">
                <label>Ketua SPPG</label>

                <input
                    type="text"
                    name="ketua_sppg"
                    class="form-control"
                    value="{{ $sppg->ketua_sppg }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Mitra / Yayasan</label>

                <input
                    type="text"
                    name="mitra_yayasan"
                    class="form-control"
                    value="{{ $sppg->mitra_yayasan }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Jumlah Pegawai</label>

                <input
                    type="number"
                    name="jumlah_pegawai"
                    class="form-control"
                    value="{{ $sppg->jumlah_pegawai }}">
            </div>

            <div class="col-md-12 mb-3">
                <label>Link Google Maps</label>

                <input
                    type="text"
                    name="link_google_maps"
                    class="form-control"
                    value="{{ $sppg->link_google_maps }}">
            </div>

            <div class="col-md-4 mb-4">

            <label class="fw-bold mb-2">Foto Depan</label>

            <label for="foto_depan" class="edit-photo-frame">

                <img
                    id="preview_depan"
                    src="{{ $sppg->foto_depan ? asset('storage/'.$sppg->foto_depan) : asset('images/no-image.png') }}"
                    class="edit-photo-preview">

            </label>

            <input
                type="file"
                id="foto_depan"
                name="foto_depan"
                class="d-none"
                accept="image/*">

            <small class="text-muted d-block mt-2">
                Klik gambar untuk mengganti
            </small>

        </div>


        <div class="col-md-4 mb-4">

            <label class="fw-bold mb-2">Foto Dapur</label>

            <label for="foto_dapur" class="edit-photo-frame">

                <img
                    id="preview_dapur"
                    src="{{ $sppg->foto_dapur ? asset('storage/'.$sppg->foto_dapur) : asset('images/no-image.png') }}"
                    class="edit-photo-preview">

            </label>

            <input
                type="file"
                id="foto_dapur"
                name="foto_dapur"
                class="d-none"
                accept="image/*">

            <small class="text-muted d-block mt-2">
                Klik gambar untuk mengganti
            </small>

        </div>


        <div class="col-md-4 mb-4">

            <label class="fw-bold mb-2">Foto Belakang</label>

            <label for="foto_belakang" class="edit-photo-frame">

                <img
                    id="preview_belakang"
                    src="{{ $sppg->foto_belakang ? asset('storage/'.$sppg->foto_belakang) : asset('images/no-image.png') }}"
                    class="edit-photo-preview">

            </label>

            <input
                type="file"
                id="foto_belakang"
                name="foto_belakang"
                class="d-none"
                accept="image/*">

            <small class="text-muted d-block mt-2">
                Klik gambar untuk mengganti
            </small>

        </div>
        </div>


        <button class="btn btn-warning mb-4">
            <i class="bi bi-floppy-fill me-2"></i>
            Update Data
        </button>

    </form>

@endsection
@section('scripts')

<script>

function previewImage(inputId, previewId){

    document
    .getElementById(inputId)
    .addEventListener('change',function(e){

        if(e.target.files.length>0){

            document
            .getElementById(previewId)
            .src = URL.createObjectURL(e.target.files[0]);

        }

    });

}

previewImage('foto_depan','preview_depan');
previewImage('foto_dapur','preview_dapur');
previewImage('foto_belakang','preview_belakang');

</script>
@endsection