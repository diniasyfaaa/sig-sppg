@extends('layouts.admin')

@section('title', 'Detail SPPG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/detail-sppg.css') }}">
@endsection

@section('content')

    <div class="user-page-container user-detail-sppg">
        <div class="detail-back-wrapper">
            <a href="javascript:history.back()" class="back-link">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

    </div>

    <div class="row align-items-start">

        {{-- =========================
        INFORMASI SPPG
        ========================== --}}
        <div class="col-lg-7 mb-4">

            <h2 class="detail-title">
                {{ $sppg->nama_sppg }}
            </h2>

            <p class="detail-address">
                {{ $sppg->alamat }}
            </p>

            <div class="detail-list">

                <div class="detail-item">
                    <div class="detail-label">Desa</div>
                    <div class="detail-colon">:</div>
                    <div class="detail-value">
                        {{ $sppg->desa->nama_desa }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Tanggal Peresmian</div>
                    <div class="detail-colon">:</div>
                    <div class="detail-value">
                        {{ \Carbon\Carbon::parse($sppg->tanggal_peresmian)->format('d F Y') }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Ketua SPPG</div>
                    <div class="detail-colon">:</div>
                    <div class="detail-value">
                        {{ $sppg->ketua_sppg }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Mitra / Yayasan</div>
                    <div class="detail-colon">:</div>
                    <div class="detail-value">
                        {{ $sppg->mitra_yayasan }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Jumlah Pegawai</div>
                    <div class="detail-colon">:</div>
                    <div class="detail-value">
                        {{ $sppg->jumlah_pegawai }} Orang
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Status SPPG</div>
                    <div class="detail-colon">:</div>
                    <div class="detail-value">

                        @if($sppg->status == 'Aktif')

                            <span class="status-active">
                                Aktif
                            </span>

                        @else

                            <span class="status-nonactive">
                                Tidak Aktif
                            </span>

                        @endif

                    </div>
                </div>

                <div class="detail-item">

                    <div class="detail-label">
                        Kapasitas Produksi
                    </div>

                    <div class="detail-colon">
                        :
                    </div>

                    <div class="detail-value">

                        {{ number_format(
        $sppg->sasaran->sum('kapasitas'),
        0,
        ',',
        '.'
    ) }}
                        Porsi / Hari

                        <br><br>

                        <a href="{{ route('sasaran.index', $sppg->id) }}" class="btn-main">
                            <i class="bi bi-people-fill"></i>
                            Lihat Detail Sasaran
                        </a>

                    </div>

                </div>

            </div>

        </div>

        {{-- KANAN FOTO --}}
        <div class="col-lg-5">


            {{-- FOTO DEPAN --}}
            <div class="mb-3">

                @if($sppg->foto_depan)

                    <img src="{{ asset('storage/' . $sppg->foto_depan) }}" class="photo-main shadow"
                        alt="Foto Depan {{ $sppg->nama_sppg }}">

                @else

                    <div class="photo-placeholder shadow">

                        Foto Depan SPPG

                    </div>

                @endif

            </div>



            <div class="row g-3">


                <div class="col-6">

                    @if($sppg->foto_dapur)

                        <img src="{{ asset('storage/' . $sppg->foto_dapur) }}" class="photo-small shadow"
                            alt="Foto Dapur {{ $sppg->nama_sppg }}">

                    @else

                        <div class="photo-small-placeholder shadow">
                            Foto Dapur
                        </div>

                    @endif

                </div>



                <div class="col-6">

                    @if($sppg->foto_belakang)

                        <img src="{{ asset('storage/' . $sppg->foto_belakang) }}" class="photo-small shadow"
                            alt="Foto Belakang {{ $sppg->nama_sppg }}">

                    @else

                        <div class="photo-small-placeholder shadow">
                            Foto Belakang
                        </div>

                    @endif


                </div>


            </div>


        </div>


    </div>

    {{-- =========================
    MAP & LOKASI
    ========================== --}}

    <div class="row mt-5">

        {{-- MAP --}}
        <div class="col-lg-7 mb-4">

            <div class="card shadow border-0 rounded-4">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Peta Lokasi SPPG
                    </h5>

                    <iframe
                        src="https://maps.google.com/maps?q={{ $sppg->latitude }},{{ $sppg->longitude }}&z=17&output=embed"
                        width="100%" height="350" style="border:0;border-radius:15px;" loading="lazy" allowfullscreen>
                    </iframe>

                </div>

            </div>

        </div>

        {{-- LOKASI --}}
        <div class="col-lg-5 d-flex align-items-center">

            <div class="w-100">

                <h4 class="fw-bold mb-4">
                    Lokasi
                </h4>

                <div class="mb-4">
                    <label class="fw-semibold text-secondary">Latitude</label>
                    <p class="border-bottom pb-2 mb-0">
                        {{ $sppg->latitude }}
                    </p>
                </div>

                <div class="mb-4">
                    <label class="fw-semibold text-secondary">Longitude</label>
                    <p class="border-bottom pb-2 mb-0">
                        {{ $sppg->longitude }}
                    </p>
                </div>

                @if($sppg->link_google_maps)
                    <a href="{{ $sppg->link_google_maps }}" target="_blank" class="btn-main w-100 mb-4">
                        <i class="bi bi-geo-alt-fill"></i>
                        Lihat di Google Maps
                    </a>
                @endif

                <div class="d-flex gap-2">

                    <a href="{{ route('sppg.edit', $sppg->id) }}" class="btn-action btn-edit flex-fill">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit
                    </a>

                    <form action="{{ route('sppg.destroy', $sppg->id) }}" method="POST" class="flex-fill">

                        @csrf

                        <button type="submit" class="btn-action btn-delete w-100"
                            onclick="return confirm('Yakin ingin menghapus data SPPG ini?')">
                            <i class="bi bi-trash me-2"></i>
                            Hapus
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card menu-harian-card shadow border-0 rounded-4">
                <div class="card-body p-4">
                    <h3 class="menu-harian-title">
                        Menu Harian
                    </h3>

                    @if(!isset($menuHarianGrouped) || $menuHarianGrouped->isEmpty())
                        <div class="alert alert-light border text-center py-4 rounded-3">
                            <i class="bi bi-info-circle text-muted fs-4 d-block mb-2"></i>
                            <span class="text-secondary">Belum ada data menu harian yang dipublikasikan untuk unit SPPG ini.</span>
                        </div>
                    @else
                        @php
                            $daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            $hasMinggu = false;
                            foreach($menuHarianGrouped as $items) {
                                if($items->where('hari', 'Minggu')->count() > 0) {
                                    $hasMinggu = true;
                                    break;
                                }
                            }
                            if($hasMinggu) {
                                $daysOrder[] = 'Minggu';
                            }
                        @endphp

                        @foreach($menuHarianGrouped as $siklusKe => $items)
                            <div class="menu-siklus-block">
                                <div class="menu-siklus-header">
                                    Minggu ke-{{ $siklusKe }}
                                </div>
                                <div class="menu-table-wrapper">
                                    <table class="menu-grid-table">
                                        <thead>
                                            <tr>
                                                @foreach($daysOrder as $dayName)
                                                    <th style="width: {{ 100 / count($daysOrder) }}%;">{{ $dayName }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach($daysOrder as $dayName)
                                                    @php
                                                        $menu = $items->firstWhere('hari', $dayName);
                                                    @endphp
                                                    <td>
                                                        <div class="menu-day-col">
                                                            <div class="menu-img-box">
                                                                @if($menu && $menu->foto)
                                                                    <a href="{{ asset('storage/' . $menu->foto) }}" target="_blank" class="w-100 h-100 d-block">
                                                                        <img src="{{ asset('storage/' . $menu->foto) }}" alt="Foto {{ $menu->menu_makanan }}">
                                                                    </a>
                                                                @elseif($menu)
                                                                    <div class="menu-img-placeholder">
                                                                        <i class="bi bi-card-image fs-3 mb-1"></i>
                                                                        <span>Foto Menu</span>
                                                                    </div>
                                                                @else
                                                                    <div class="menu-img-placeholder">
                                                                        <i class="bi bi-slash-circle fs-4 mb-1"></i>
                                                                        <span>Tidak Ada</span>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="menu-desc-text">
                                                                @if($menu && $menu->menu_makanan)
                                                                    {{ $menu->menu_makanan }}
                                                                @else
                                                                    <span class="text-muted italic">-</span>
                                                                @endif
                                                            </div>

                                                            <div class="menu-gizi-text">
                                                                @if($menu && $menu->standar_gizi)
                                                                    {!! nl2br(e($menu->standar_gizi)) !!}
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>


@endsection