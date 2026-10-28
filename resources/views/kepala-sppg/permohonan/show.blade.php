@extends('layouts.kepala')

@section('title', 'Detail Permohonan Update')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">Detail Permohonan #{{ $permohonan->id }}</h2>
            <p class="text-muted m-0">Diajukan pada {{ $permohonan->created_at->format('d F Y, H:i') }} WIB</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <a href="{{ route('kepala.permohonan.index') }}" class="btn btn-outline-secondary px-3"
                style="border-radius: 10px;">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-4 align-items-stretch">
        <div class="col-md-8 d-flex">
            <div class="card border-0 shadow-sm w-100 d-flex flex-column" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0 text-dark">
                        <i class="bi bi-info-circle text-primary me-2"></i> Rincian Pengajuan
                    </h5>
                    <div>
                        @if($permohonan->status === 'pending')
                            <span class="badge bg-warning text-dark px-3 py-2 fs-6"><i class="bi bi-clock me-1"></i> Menunggu
                                Persetujuan Admin</span>
                        @elseif($permohonan->status === 'disetujui')
                            <span class="badge bg-success px-3 py-2 fs-6"><i class="bi bi-check-circle me-1"></i> Permohonan
                                Disetujui</span>
                        @else
                            <span class="badge bg-danger px-3 py-2 fs-6"><i class="bi bi-x-circle me-1"></i> Permohonan
                                Ditolak</span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-4 border-top flex-fill d-flex flex-column justify-content-between">
                    <div>
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td width="200" class="text-muted fw-semibold">Kode Permohonan</td>
                                    <td>: <strong>{{ $permohonan->kode ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Jenis Permohonan</td>
                                    <td>:
                                        @if($permohonan->jenis === 'perubahan_data')
                                            <span class="badge bg-info text-dark">Perubahan Data SPPG</span>
                                        @else
                                            <span class="badge bg-success">Perubahan Data Sasaran</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Unit SPPG</td>
                                    <td>: <strong>{{ $permohonan->sppg->nama_sppg }}</strong> <span class="text-secondary ms-1" style="font-size: 0.85rem;">({{ $permohonan->sppg->kode ?? '-' }})</span> (Desa
                                        {{ $permohonan->sppg->desa->nama_desa ?? '-' }})</td>
                                </tr>

                                @if($permohonan->jenis === 'perubahan_data')
                                    <tr>
                                        <td class="text-muted fw-semibold">Kolom / Data Diubah</td>
                                        <td>: <span
                                                class="badge bg-light text-dark border px-2 py-1 fs-6">{{ $permohonan->field_perubahan }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted fw-semibold align-top">Usulan Nilai Baru</td>
                                        <td>:
                                            @if(in_array($permohonan->field_perubahan, ['Foto Depan', 'Foto Dapur', 'Foto Belakang']))
                                                <div class="d-inline-block mt-1">
                                                    <a href="{{ asset('storage/' . $permohonan->nilai_baru) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $permohonan->nilai_baru) }}"
                                                            alt="Usulan Foto Baru" class="img-thumbnail rounded shadow-sm"
                                                            style="max-height: 180px; object-fit: cover;">
                                                    </a>
                                                    <small class="d-block text-muted mt-1"><i class="bi bi-search me-1"></i> Klik
                                                        gambar untuk melihat ukuran penuh</small>
                                                </div>
                                            @elseif($permohonan->field_perubahan === 'Desa')
                                                @php $desaObj = \App\Models\Desa::find($permohonan->nilai_baru); @endphp
                                                <strong
                                                    class="text-primary fs-6">{{ $desaObj->nama_desa ?? $permohonan->nilai_baru }}</strong>
                                            @elseif($permohonan->field_perubahan === 'Link Google Maps')
                                                <a href="{{ $permohonan->nilai_baru }}" target="_blank"
                                                    class="text-primary text-break fw-semibold">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i> {{ $permohonan->nilai_baru }}
                                                </a>
                                            @else
                                                <strong class="text-primary fs-6">{{ $permohonan->nilai_baru }}</strong>
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-muted fw-semibold">Nama Sasaran</td>
                                        <td>: <strong class="text-success fs-6">{{ $permohonan->nama_sasaran }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted fw-semibold">Kapasitas Sasaran</td>
                                        <td>: {{ $permohonan->kapasitas_sasaran }} Orang</td>
                                    </tr>
                                    @if($permohonan->jumlah_pegawai_baru)
                                        <tr>
                                            <td class="text-muted fw-semibold">Perubahan Jml Pegawai</td>
                                            <td>: {{ $permohonan->jumlah_pegawai_baru }} Orang</td>
                                        </tr>
                                    @endif
                                @endif

                                @if($permohonan->deskripsi)
                                    <tr>
                                        <td class="text-muted fw-semibold align-top">Catatan Keterangan</td>
                                        <td>: {{ $permohonan->deskripsi }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        @if($permohonan->status === 'ditolak' && $permohonan->alasan_penolakan)
                            <div class="mt-4 pt-3 border-top">
                                <div class="text-muted small fw-semibold mb-1">
                                    <i class="bi bi-info-circle me-1 text-danger"></i> Catatan Alasan Penolakan dari Admin:
                                </div>
                                <div class="p-3 bg-light rounded-3 text-dark border-start border-4 border-danger small">
                                    {{ $permohonan->alasan_penolakan }}
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('kepala.permohonan.create') }}"
                                        class="btn btn-primary btn-sm px-3 py-2 fw-semibold" style="border-radius: 8px;">
                                        <i class="bi bi-plus-circle me-1"></i> Buat Permohonan Baru
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Dokumen Landasan --}}
        <div class="col-md-4 d-flex">
            <div class="card border-0 shadow-sm w-100 d-flex flex-column" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold m-0 text-dark">
                        <i class="bi bi-file-earmark-image text-primary me-2"></i> Dokumen Landasan
                    </h5>
                </div>
                <div class="card-body p-4 text-center border-top flex-fill d-flex flex-column justify-content-between">
                    @if($permohonan->foto_landasan)
                        @php
                            $ext = strtolower(pathinfo($permohonan->foto_landasan, PATHINFO_EXTENSION));
                        @endphp

                        <div class="d-flex align-items-center justify-content-center flex-fill mb-3 overflow-hidden"
                            style="min-height: 220px;">
                            @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                <a href="{{ asset('storage/' . $permohonan->foto_landasan) }}" target="_blank"
                                    class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <img src="{{ asset('storage/' . $permohonan->foto_landasan) }}" alt="Foto Landasan Perubahan"
                                        class="img-fluid rounded border shadow-sm"
                                        style="max-height: 380px; width: 100%; object-fit: cover; object-position: center;">
                                </a>
                            @else
                                <div class="w-100 py-4 bg-light rounded border">
                                    <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                                    <div class="small fw-semibold mt-2">Dokumen Landasan (PDF)</div>
                                </div>
                            @endif
                        </div>

                        <a href="{{ asset('storage/' . $permohonan->foto_landasan) }}" target="_blank"
                            class="btn btn-outline-primary btn-sm w-100 py-2 mt-auto" style="border-radius: 10px;">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka / Download File Landasan
                        </a>
                    @else
                        <div class="text-muted my-auto py-3">Tidak ada lampiran dokumen</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection