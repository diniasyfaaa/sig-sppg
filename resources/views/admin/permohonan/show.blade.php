@extends('layouts.admin')

@section('title', 'Tinjau Permohonan Update')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
@endsection

@section('content')

    <div class="dashboard-header mb-4">
        <div class="header-left">
            <h2>Tinjau Permohonan Update #{{ $permohonan->id }}</h2>
            <p>Pengajuan dari Kepala SPPG {{ $permohonan->user->name ?? '' }} pada {{ $permohonan->created_at->format('d F Y, H:i') }} WIB</p>
        </div>
        <div>
            <a href="{{ route('admin.permohonan.index') }}" class="back-link">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="row g-4 align-items-stretch">
        {{-- Detail Permohonan --}}
        <div class="col-lg-8 d-flex">
            <div class="card border-0 shadow-sm w-100 d-flex flex-column" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0 text-dark">
                        <i class="bi bi-card-checklist text-primary me-2"></i> Detail Pengajuan Update Data
                    </h5>
                    <div>
                        @if($permohonan->status === 'pending')
                            <span class="badge bg-warning text-dark px-3 py-2 fs-6"><i class="bi bi-clock me-1"></i> Perlu Tindakan Admin</span>
                        @elseif($permohonan->status === 'disetujui')
                            <span class="badge bg-success px-3 py-2 fs-6"><i class="bi bi-check-circle me-1"></i> Disetujui</span>
                        @else
                            <span class="badge bg-danger px-3 py-2 fs-6"><i class="bi bi-x-circle me-1"></i> Ditolak</span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-4 border-top flex-fill d-flex flex-column justify-content-between">
                    <div>
                        <div class="p-3 bg-light rounded mb-4 border">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Unit SPPG Pemohon:</small>
                                    <strong class="fs-6 text-dark"><i class="bi bi-building me-1"></i> {{ $permohonan->sppg->nama_sppg ?? '-' }}</strong>
                                    <span class="text-secondary ms-1" style="font-size: 0.85rem;">({{ $permohonan->sppg->kode ?? '-' }})</span>
                                    <small class="d-block text-muted mt-1">Desa {{ $permohonan->sppg->desa->nama_desa ?? '-' }} (Kode Pos: {{ $permohonan->sppg->desa->kode_pos ?? '-' }})</small>
                                </div>
                                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                    <small class="text-muted d-block">Kepala SPPG (Pemohon):</small>
                                    <strong class="text-primary"><i class="bi bi-person-fill me-1"></i> {{ $permohonan->user->name ?? '-' }}</strong>
                                    <small class="d-block text-muted">{{ $permohonan->user->email ?? '-' }}</small>
                                </div>
                            </div>
                        </div>

                        <table class="table table-borderless align-middle mb-4">
                            <tbody>
                                <tr>
                                    <td width="220" class="text-muted fw-semibold">Kode Permohonan</td>
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

                                @if($permohonan->jenis === 'perubahan_data')
                                    <tr>
                                        <td class="text-muted fw-semibold">Kolom / Field Yang Diubah</td>
                                        <td>: <span class="badge bg-secondary px-2 py-1 fs-6">{{ $permohonan->field_perubahan }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted fw-semibold align-top">Usulan Nilai Baru</td>
                                        <td>: 
                                            @if(in_array($permohonan->field_perubahan, ['Foto Depan', 'Foto Dapur', 'Foto Belakang']))
                                                <div class="d-inline-block mt-1">
                                                    <a href="{{ asset('storage/' . $permohonan->nilai_baru) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $permohonan->nilai_baru) }}" alt="Usulan Foto Baru" class="img-thumbnail rounded shadow-sm" style="max-height: 180px; object-fit: cover;">
                                                    </a>
                                                    <small class="d-block text-muted mt-1"><i class="bi bi-search me-1"></i> Klik gambar untuk melihat ukuran penuh</small>
                                                </div>
                                            @elseif($permohonan->field_perubahan === 'Desa')
                                                @php $desaObj = \App\Models\Desa::find($permohonan->nilai_baru); @endphp
                                                <strong class="text-primary fs-6">{{ $desaObj->nama_desa ?? $permohonan->nilai_baru }}</strong>
                                            @elseif($permohonan->field_perubahan === 'Link Google Maps')
                                                <a href="{{ $permohonan->nilai_baru }}" target="_blank" class="text-primary text-break fw-semibold">
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
                            <div class="mt-4 pt-3 border-top mb-4">
                                <div class="text-muted small fw-semibold mb-1">
                                    <i class="bi bi-info-circle me-1 text-danger"></i> Catatan Alasan Penolakan:
                                </div>
                                <div class="p-3 bg-light rounded-3 text-dark border-start border-4 border-danger small">
                                    {{ $permohonan->alasan_penolakan }}
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Tombol Aksi Approve / Reject jika pending --}}
                    @if($permohonan->status === 'pending')
                        <div class="p-3 bg-white border rounded shadow-sm d-flex gap-2 justify-content-end align-items-center mt-3">
                            <span class="fw-bold me-auto text-muted"><i class="bi bi-lightning-charge me-1"></i> Keputusan Admin:</span>

                            <form action="{{ route('admin.permohonan.approve', $permohonan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI permohonan ini? Data SPPG akan otomatis diperbarui.')">
                                @csrf
                                <button type="submit" class="btn btn-success px-4 py-2" style="border-radius: 10px;">
                                    <i class="bi bi-check-circle me-1"></i> Disetujui (Approve)
                                </button>
                            </form>

                            <button type="button" class="btn btn-danger px-4 py-2" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#modalReject">
                                <i class="bi bi-x-circle me-1"></i> Tolak (Reject)
                            </button>
                        </div>
                    @else
                        <div class="text-muted small mt-3">
                            Status permohonan ini telah diproses pada {{ $permohonan->approved_at ? \Carbon\Carbon::parse($permohonan->approved_at)->format('d F Y, H:i') . ' WIB' : '-' }}.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Dokumen Landasan --}}
        <div class="col-lg-4 d-flex">
            <div class="card border-0 shadow-sm w-100 d-flex flex-column" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold m-0 text-dark">
                        <i class="bi bi-file-earmark-check text-primary me-2"></i> Dokumen Landasan
                    </h5>
                </div>
                <div class="card-body p-4 text-center border-top flex-fill d-flex flex-column justify-content-between">
                    @if($permohonan->foto_landasan)
                        @php
                            $ext = strtolower(pathinfo($permohonan->foto_landasan, PATHINFO_EXTENSION));
                        @endphp

                        <div class="d-flex align-items-center justify-content-center flex-fill mb-3 overflow-hidden" style="min-height: 250px;">
                            @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                <a href="{{ asset('storage/' . $permohonan->foto_landasan) }}" target="_blank" class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <img
                                        src="{{ asset('storage/' . $permohonan->foto_landasan) }}"
                                        alt="Foto Landasan Perubahan"
                                        class="img-fluid rounded border shadow-sm"
                                        style="max-height: 380px; width: 100%; object-fit: cover; object-position: center;"
                                    >
                                </a>
                            @else
                                <div class="w-100 py-4 bg-light rounded border">
                                    <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                                    <div class="small fw-semibold mt-2">Dokumen Landasan (PDF)</div>
                                </div>
                            @endif
                        </div>

                        <a
                            href="{{ asset('storage/' . $permohonan->foto_landasan) }}"
                            target="_blank"
                            class="btn btn-outline-primary btn-sm w-100 py-2 mt-auto"
                            style="border-radius: 10px;"
                        >
                            <i class="bi bi-box-arrow-up-right me-1"></i> Lihat / Download File Dokumen
                        </a>
                    @else
                        <div class="text-muted my-auto py-3">Tidak ada lampiran foto/dokumen landasan</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL REJECT PERMOHONAN --}}
    @if($permohonan->status === 'pending')
        <div class="modal fade" id="modalReject" tabindex="-1" aria-labelledby="modalRejectLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 16px;">
                    <form action="{{ route('admin.permohonan.reject', $permohonan->id) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-danger text-white" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                            <h5 class="modal-title fw-bold" id="modalRejectLabel">
                                <i class="bi bi-x-circle me-1"></i> Tolak Permohonan Update Data
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="text-dark">
                                Berikan alasan penolakan permohonan dari Kepala SPPG <strong>{{ $permohonan->user->name ?? '' }}</strong>. Alasan ini wajib diisi agar Kepala SPPG mengetahui kendala pada permohonan tersebut.
                            </p>

                            <div class="mb-3">
                                <label for="alasan_penolakan" class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea
                                    name="alasan_penolakan"
                                    id="alasan_penolakan"
                                    rows="4"
                                    class="form-control @error('alasan_penolakan') is-invalid @enderror"
                                    placeholder="Contoh: Dokumen landasan perubahan tidak jelas / tidak sah, atau nilai usulan tidak sesuai dengan fakta lapangan."
                                    required
                                ></textarea>
                                @error('alasan_penolakan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="bi bi-x-circle me-1"></i> Konfirmasi Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

@endsection
