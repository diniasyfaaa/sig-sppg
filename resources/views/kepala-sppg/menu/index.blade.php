@extends('layouts.kepala')

@section('title', 'Kelola Menu Makanan Harian')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
@endsection

@section('content')

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Header Area --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">Kelola Menu Makanan Harian</h2>
            <p class="text-muted m-0">Unit SPPG: <strong>{{ $sppg->nama_sppg }}</strong> (Desa
                {{ $sppg->desa->nama_desa ?? '-' }})
            </p>
        </div>
        <div>
            <a href="{{ route('kepala.menu.create') }}" class="btn-main-sm fs-6 py-2 px-3">
                <i class="bi bi-plus-circle me-1"></i> Tambah Menu Harian
            </a>
        </div>
    </div>

        {{-- Notice Box --}}
        <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4" style="background-color: #eef7ff;">
            <div class="d-flex align-items-start">
                <i class="bi bi-info-circle-fill text-info fs-4 me-3 mt-1"></i>
                <div>
                    <strong class="text-dark">Ketentuan Pengajuan Menu Harian:</strong>
                    <ul class="mb-0 mt-1 ps-3 text-secondary small">
                        <li>Menu harian yang Anda tambahkan atau edit akan berstatus <strong>Pending</strong> terlebih
                            dahulu.</li>
                        <li>Setelah <strong>Disetujui</strong> oleh Administrator, menu harian akan secara otomatis
                            ditampilkan di halaman publik detail SPPG Anda.</li>
                        <li>Apabila permohonan menu <strong>Ditolak</strong>, Anda dapat melihat alasan penolakan dan
                            memperbaruinya kembali.</li>
                    </ul>
                </div>
            </div>
        </div>

        @if($menus->isEmpty())
            <div class="card border-0 shadow-sm text-center py-5 rounded-4">
                <div class="card-body">
                    <i class="bi bi-journal-x text-muted display-4 d-block mb-3"></i>
                    <h5 class="fw-bold text-dark">Belum Ada Menu Harian</h5>
                    <p class="text-muted small">Anda belum menambahkan siklus menu makanan harian untuk SPPG ini.</p>
                </div>
            </div>
        @else
            {{-- Display Menu Per Siklus --}}
            @foreach($menus as $siklusKe => $items)
                <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
                    <div class="card-header text-white py-3 d-flex justify-content-between align-items-center" style="background-color: #0f2d49;">
                        <h5 class="m-0 fw-bold">
                            <i class="bi bi-calendar-week me-2"></i> Siklus Minggu ke-{{ $siklusKe }}
                        </h5>
                        <span class="badge bg-white text-primary fw-semibold fs-6">
                            {{ $items->count() }} Hari Terdaftar
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;" class="ps-3">No</th>
                                    <th style="width: 100px;">Hari</th>
                                    <th style="width: 200px;">Menu Makanan</th>
                                    <th>Standar Gizi</th>
                                    <th style="width: 100px;">Foto</th>
                                    <th style="width: 130px;">Status Admin</th>
                                    <th style="width: 140px;" class="text-end pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td class="ps-3">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="text-dark">
                                            {{ $item->hari }}
                                        </td>
                                        <td>
                                            <span class="text-dark">{{ $item->menu_makanan }}</span>
                                        </td>
                                        <td>
                                            @if($item->standar_gizi)
                                                <span class="small text-secondary">{{ Str::limit($item->standar_gizi, 80) }}</span>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->foto)
                                                <a href="{{ asset('storage/' . $item->foto) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Menu"
                                                        class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status == 'disetujui')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-normal px-2.5 py-1">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                                </span>
                                            @elseif($item->status == 'pending')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-normal px-2.5 py-1">
                                                    <i class="bi bi-clock-fill me-1"></i> Pending
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-normal px-2.5 py-1"
                                                    data-bs-toggle="tooltip" title="{{ $item->alasan_penolakan }}">
                                                    <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                                </span>
                                                @if($item->alasan_penolakan)
                                                    <small class="d-block text-danger mt-1" style="font-size: 11px;">
                                                        Alasan: {{ $item->alasan_penolakan }}
                                                    </small>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <div class="d-inline-flex align-items-center justify-content-end gap-1 flex-wrap">
                                                <a href="{{ route('kepala.menu.edit', $item->id) }}" class="btn btn-sm btn-primary rounded-2 px-2.5 py-1 fw-normal"
                                                    title="Edit Menu">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger rounded-2 px-2.5 py-1 fw-normal" title="Hapus Menu"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </div>

                                            {{-- Delete Modal --}}
                                            <div class="modal fade text-start" id="deleteModal{{ $item->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title fw-bold">
                                                                <i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <p class="m-0 text-dark">
                                                                Apakah Anda yakin ingin menghapus menu makanan
                                                                <strong>"{{ $item->menu_makanan }}"</strong> (Siklus Minggu
                                                                ke-{{ $item->siklus_ke }} - Hari {{ $item->hari }})?
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary rounded-3"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <form action="{{ route('kepala.menu.destroy', $item->id) }}" method="POST"
                                                                class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger rounded-3">
                                                                    <i class="bi bi-trash-fill me-1"></i> Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif
@endsection