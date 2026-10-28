@extends('layouts.kepala')

@section('title', 'Buat Permohonan Update Data')

@section('styles')
    <style>
        .permohonan-type-option {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            border: 2px solid #e2e8f0 !important;
            border-radius: 14px !important;
            cursor: pointer;
            user-select: none;
            background-color: #ffffff;
        }

        .permohonan-type-option:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
            border-color: #cbd5e1 !important;
        }

        .permohonan-type-option.active-perubahan {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(15, 45, 73, 0.15) !important;
            border-color: #0f2d49 !important;
            background-color: #f8fafc;
        }

        .permohonan-type-option.active-penambahan {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(25, 135, 84, 0.15) !important;
            border-color: #198754 !important;
            background-color: #f8fafc;
        }
    </style>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">Buat Permohonan Update Data</h2>
            <p class="text-muted m-0">Ajukan permohonan perubahan data SPPG atau penambahan sasaran kepada Administrator</p>
        </div>
        <a href="{{ route('kepala.permohonan.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terdapat kesalahan pengisian form:</h6>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            <form action="{{ route('kepala.permohonan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4 p-3 bg-light rounded border">
                    <small class="text-muted d-block mb-1">Unit SPPG Yang Diwakili:</small>
                    <h5 class="fw-bold text-primary m-0"><i class="bi bi-building me-2"></i> {{ $sppg->nama_sppg }}</h5>
                    <small class="text-muted">Desa {{ $sppg->desa->nama_desa ?? '-' }} | Ketua:
                        {{ $sppg->ketua_sppg ?? '-' }}</small>
                </div>

                {{-- Pilih Jenis Permohonan --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Jenis Permohonan <span class="text-danger">*</span></label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="jenisPerubahan" class="card h-100 p-3 permohonan-type-option"
                                id="typeOptionPerubahan">
                                <div class="form-check pointer-events-none">
                                    <input class="form-check-input" type="radio" name="jenis" id="jenisPerubahan"
                                        value="perubahan_data" {{ old('jenis', request('jenis', 'perubahan_data')) === 'perubahan_data' ? 'checked' : '' }}>
                                    <span class="form-check-label fw-bold text-dark fs-6 ms-1">
                                        <i class="bi bi-pencil-square text-primary me-2"></i> Perubahan Data SPPG
                                    </span>
                                </div>
                                <p class="text-muted small mt-2 mb-0 ms-4">
                                    Mengajukan perubahan informasi SPPG yang sudah ada (misal: nama, alamat, jumlah pegawai,
                                    ketua, mitra, dll).
                                </p>
                            </label>
                        </div>

                        <div class="col-md-6">
                            <label for="jenisPenambahan" class="card h-100 p-3 permohonan-type-option"
                                id="typeOptionPenambahan">
                                <div class="form-check pointer-events-none">
                                    <input class="form-check-input" type="radio" name="jenis" id="jenisPenambahan"
                                        value="penambahan_sasaran" {{ old('jenis', request('jenis')) === 'penambahan_sasaran' ? 'checked' : '' }}>
                                    <span class="form-check-label fw-bold text-dark fs-6 ms-1">
                                        <i class="bi bi-people text-success me-2"></i> Perubahan Data Sasaran
                                    </span>
                                </div>
                                <p class="text-muted small mt-2 mb-0 ms-4">
                                    Mengajukan perubahan atau penambahan data sasaran penerima beserta kapasitasnya.
                                </p>
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- SECTION 1: PERUBAHAN DATA SPPG --}}
                <div id="sectionPerubahanData">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bi bi-sliders me-2"></i> Detail Perubahan Data SPPG
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="field_perubahan" class="form-label fw-bold">Pilih Kolom / Data Yang Ingin Diubah
                                <span class="text-danger">*</span></label>
                            <select name="field_perubahan" id="field_perubahan"
                                class="form-select @error('field_perubahan') is-invalid @enderror">
                                <option value="">-- Pilih Data Yang Ingin Diubah --</option>
                                <option value="Nama SPPG" {{ old('field_perubahan') === 'Nama SPPG' ? 'selected' : '' }}>Nama
                                    SPPG</option>
                                <option value="Desa" {{ old('field_perubahan') === 'Desa' ? 'selected' : '' }}>Wilayah Desa
                                </option>
                                <option value="Alamat Lengkap" {{ old('field_perubahan') === 'Alamat Lengkap' ? 'selected' : '' }}>Alamat Lengkap</option>
                                <option value="Nama Ketua SPPG" {{ old('field_perubahan') === 'Nama Ketua SPPG' ? 'selected' : '' }}>Nama Ketua SPPG</option>
                                <option value="Mitra / Yayasan" {{ old('field_perubahan') === 'Mitra / Yayasan' ? 'selected' : '' }}>Mitra / Yayasan Pengelola</option>
                                <option value="Jumlah Pegawai" {{ old('field_perubahan') === 'Jumlah Pegawai' ? 'selected' : '' }}>Jumlah Pegawai / Pengurus</option>
                                <option value="Status Operasional" {{ old('field_perubahan') === 'Status Operasional' ? 'selected' : '' }}>Status Operasional (Aktif / Tidak Aktif)</option>
                                <option value="Tanggal Peresmian" {{ old('field_perubahan') === 'Tanggal Peresmian' ? 'selected' : '' }}>Tanggal Peresmian</option>
                                <option value="Link Google Maps" {{ old('field_perubahan') === 'Link Google Maps' ? 'selected' : '' }}>Link Google Maps / Lokasi</option>
                                <option value="Foto Depan" {{ old('field_perubahan') === 'Foto Depan' ? 'selected' : '' }}>
                                    Foto Bangunan (Tampak Depan)</option>
                                <option value="Foto Dapur" {{ old('field_perubahan') === 'Foto Dapur' ? 'selected' : '' }}>
                                    Foto Bangunan (Tampak Dapur)</option>
                                <option value="Foto Belakang" {{ old('field_perubahan') === 'Foto Belakang' ? 'selected' : '' }}>Foto Bangunan (Tampak Belakang)</option>
                            </select>
                            @error('field_perubahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6" id="container_nilai_baru">
                            <label for="nilai_baru" class="form-label fw-bold" id="label_nilai_baru">Data / Nilai Usulan
                                Baru <span class="text-danger">*</span></label>

                            {{-- Input Text / Number / Date / Url Default --}}
                            <input type="text" name="nilai_baru" id="nilai_baru"
                                class="form-control @error('nilai_baru') is-invalid @enderror"
                                value="{{ old('nilai_baru') }}" placeholder="Masukkan nilai usulan baru">

                            {{-- Textarea Alamat --}}
                            <textarea name="nilai_baru_textarea" id="nilai_baru_textarea" rows="3"
                                class="form-control d-none @error('nilai_baru') is-invalid @enderror"
                                placeholder="Masukkan alamat lengkap baru...">{{ old('nilai_baru') }}</textarea>

                            {{-- Select Desa --}}
                            <select name="nilai_baru_desa" id="nilai_baru_desa"
                                class="form-select d-none @error('nilai_baru') is-invalid @enderror">
                                <option value="">-- Pilih Wilayah Desa --</option>
                                @if(isset($desa))
                                    @foreach($desa as $d)
                                        <option value="{{ $d->id }}" {{ old('nilai_baru') == $d->id ? 'selected' : '' }}>
                                            {{ $d->nama_desa }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>

                            {{-- Select Status Operasional --}}
                            <select name="nilai_baru_status" id="nilai_baru_status"
                                class="form-select d-none @error('nilai_baru') is-invalid @enderror">
                                <option value="Aktif" {{ old('nilai_baru') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Tidak Aktif" {{ old('nilai_baru') === 'Tidak Aktif' ? 'selected' : '' }}>Tidak
                                    Aktif</option>
                            </select>

                            {{-- File Input Foto --}}
                            <div id="container_foto_preview" class="d-none">
                                <input type="file" name="nilai_baru_file" id="nilai_baru_file"
                                    class="form-control @error('nilai_baru_file') is-invalid @enderror"
                                    accept="image/jpeg,image/png,image/jpg">
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-info-circle me-1"></i> Format foto: JPG, JPEG, PNG (Maksimal 2 MB).
                                </small>
                                <div class="mt-2 text-center p-2 bg-light rounded border d-none" id="preview_foto_box">
                                    <img id="preview_foto_img" src="" alt="Preview Foto Baru"
                                        class="img-fluid rounded shadow-sm" style="max-height: 160px;">
                                </div>
                                @error('nilai_baru_file')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            @error('nilai_baru')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: PERUBAHAN DATA SASARAN --}}
                <div id="sectionPenambahanSasaran" style="display: none;">
                    <h5 class="fw-bold text-success mb-3">
                        <i class="bi bi-people me-2"></i> Detail Perubahan Data Sasaran
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6 position-relative">
                            <label for="nama_sasaran" class="form-label fw-bold">Nama Sasaran / Kelompok Sasaran <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                                <input type="text" name="nama_sasaran" id="nama_sasaran"
                                    class="form-control border-start-0 ps-0 @error('nama_sasaran') is-invalid @enderror"
                                    value="{{ old('nama_sasaran') }}"
                                    placeholder="Ketik untuk mencari sasaran yang ada atau menambah baru..." autocomplete="off">
                            </div>

                            {{-- Autocomplete Suggestion Dropdown List --}}
                            <div id="sasaranDropdown" class="dropdown-menu w-100 shadow border-0 mt-1 p-0 overflow-hidden d-none position-absolute" style="z-index: 1050; max-height: 220px; overflow-y: auto; border-radius: 12px;">
                            </div>

                            {{-- Info Notice when selecting an existing sasaran --}}
                            <div id="sasaranInfoNotice" class="alert alert-info py-2 px-3 mt-2 mb-0 d-none" style="border-radius: 10px; font-size: 0.85rem;">
                                <i class="bi bi-info-circle-fill me-1"></i> Data sasaran ditemukan di sistem. Memilih data ini akan <strong>mengubah kapasitas sasaran yang sudah ada</strong> (mencegah data ganda).
                            </div>

                            @error('nama_sasaran')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="kapasitas_sasaran" class="form-label fw-bold">Kapasitas (Jumlah Orang) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="kapasitas_sasaran" id="kapasitas_sasaran"
                                class="form-control @error('kapasitas_sasaran') is-invalid @enderror"
                                value="{{ old('kapasitas_sasaran') }}" placeholder="Contoh: 50" min="1">
                            @error('kapasitas_sasaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- DESKRIPSI & FOTO LANDASAN --}}
                <div class="row g-3">
                    <div class="col-12">
                        <label for="deskripsi" class="form-label fw-bold">Catatan / Keterangan Tambahan (Opsional)</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                            class="form-control @error('deskripsi') is-invalid @enderror"
                            placeholder="Jelaskan secara ringkas mengenai latar belakang permohonan ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="foto_landasan" class="form-label fw-bold">
                            Upload Foto / Dokumen Dasar Landasan Perubahan <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="foto_landasan" id="foto_landasan"
                            class="form-control @error('foto_landasan') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/jpg,application/pdf" required>
                        <small class="text-muted d-block mt-1">
                            <i class="bi bi-info-circle me-1"></i> Wajib melampirkan foto/file dokumen bukti landasan
                            perubahan (misal: Surat Perizinan, Akta, Sertifikat Kepemilikan, Surat Tugas, Foto Fisik, dll).
                            Format: JPG, PNG, PDF (Maks. 5MB).
                        </small>
                        @error('foto_landasan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="form-actions mt-4 pt-3 border-top d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">
                        <i class="bi bi-send me-1"></i> Kirim Permohonan Ke Admin
                    </button>
                    <a href="{{ route('kepala.permohonan.index') }}" class="btn btn-secondary px-4 py-2"
                        style="border-radius: 10px;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jenisPerubahan = document.getElementById('jenisPerubahan');
            const jenisPenambahan = document.getElementById('jenisPenambahan');
            const sectionPerubahanData = document.getElementById('sectionPerubahanData');
            const sectionPenambahanSasaran = document.getElementById('sectionPenambahanSasaran');

            const fieldPerubahan = document.getElementById('field_perubahan');
            const nilaiBaruInput = document.getElementById('nilai_baru');
            const nilaiBaruTextarea = document.getElementById('nilai_baru_textarea');
            const nilaiBaruDesa = document.getElementById('nilai_baru_desa');
            const nilaiBaruStatus = document.getElementById('nilai_baru_status');
            const containerFotoPreview = document.getElementById('container_foto_preview');
            const nilaiBaruFile = document.getElementById('nilai_baru_file');
            const labelNilaiBaru = document.getElementById('label_nilai_baru');
            const previewFotoBox = document.getElementById('preview_foto_box');
            const previewFotoImg = document.getElementById('preview_foto_img');

            const namaSasaran = document.getElementById('nama_sasaran');
            const kapasitasSasaran = document.getElementById('kapasitas_sasaran');

            const typeOptionPerubahan = document.getElementById('typeOptionPerubahan');
            const typeOptionPenambahan = document.getElementById('typeOptionPenambahan');

            function toggleSections() {
                if (jenisPerubahan.checked) {
                    if (typeOptionPerubahan) typeOptionPerubahan.classList.add('active-perubahan');
                    if (typeOptionPenambahan) typeOptionPenambahan.classList.remove('active-penambahan');

                    sectionPerubahanData.style.display = 'block';
                    sectionPenambahanSasaran.style.display = 'none';

                    fieldPerubahan.required = true;
                    namaSasaran.required = false;
                    kapasitasSasaran.required = false;
                    updateNilaiBaruInput();
                } else {
                    if (typeOptionPenambahan) typeOptionPenambahan.classList.add('active-penambahan');
                    if (typeOptionPerubahan) typeOptionPerubahan.classList.remove('active-perubahan');

                    sectionPerubahanData.style.display = 'none';
                    sectionPenambahanSasaran.style.display = 'block';

                    fieldPerubahan.required = false;
                    nilaiBaruInput.required = false;
                    nilaiBaruTextarea.required = false;
                    if (nilaiBaruDesa) nilaiBaruDesa.required = false;
                    nilaiBaruStatus.required = false;
                    nilaiBaruFile.required = false;

                    namaSasaran.required = true;
                    kapasitasSasaran.required = true;
                }
            }

            function updateNilaiBaruInput() {
                if (!jenisPerubahan.checked) return;

                const val = fieldPerubahan.value;

                // Reset inputs & names
                nilaiBaruInput.classList.add('d-none');
                nilaiBaruInput.disabled = true;
                nilaiBaruInput.required = false;
                nilaiBaruInput.removeAttribute('name');

                nilaiBaruTextarea.classList.add('d-none');
                nilaiBaruTextarea.disabled = true;
                nilaiBaruTextarea.required = false;
                nilaiBaruTextarea.removeAttribute('name');

                if (nilaiBaruDesa) {
                    nilaiBaruDesa.classList.add('d-none');
                    nilaiBaruDesa.disabled = true;
                    nilaiBaruDesa.required = false;
                    nilaiBaruDesa.removeAttribute('name');
                }

                nilaiBaruStatus.classList.add('d-none');
                nilaiBaruStatus.disabled = true;
                nilaiBaruStatus.required = false;
                nilaiBaruStatus.removeAttribute('name');

                containerFotoPreview.classList.add('d-none');
                nilaiBaruFile.disabled = true;
                nilaiBaruFile.required = false;

                labelNilaiBaru.innerHTML = 'Data / Nilai Usulan Baru <span class="text-danger">*</span>';

                if (['Foto Depan', 'Foto Dapur', 'Foto Belakang'].includes(val)) {
                    labelNilaiBaru.innerHTML = 'Upload Foto Baru (' + val + ') <span class="text-danger">*</span>';
                    containerFotoPreview.classList.remove('d-none');
                    nilaiBaruFile.disabled = false;
                    nilaiBaruFile.required = true;
                } else if (val === 'Desa') {
                    labelNilaiBaru.innerHTML = 'Pilih Wilayah Desa Baru <span class="text-danger">*</span>';
                    if (nilaiBaruDesa) {
                        nilaiBaruDesa.classList.remove('d-none');
                        nilaiBaruDesa.disabled = false;
                        nilaiBaruDesa.required = true;
                        nilaiBaruDesa.name = 'nilai_baru';
                    }
                } else if (val === 'Status Operasional') {
                    labelNilaiBaru.innerHTML = 'Pilih Status Operasional Baru <span class="text-danger">*</span>';
                    nilaiBaruStatus.classList.remove('d-none');
                    nilaiBaruStatus.disabled = false;
                    nilaiBaruStatus.required = true;
                    nilaiBaruStatus.name = 'nilai_baru';
                } else if (val === 'Alamat Lengkap') {
                    labelNilaiBaru.innerHTML = 'Alamat Lengkap Baru <span class="text-danger">*</span>';
                    nilaiBaruTextarea.classList.remove('d-none');
                    nilaiBaruTextarea.disabled = false;
                    nilaiBaruTextarea.required = true;
                    nilaiBaruTextarea.name = 'nilai_baru';
                } else if (val === 'Tanggal Peresmian') {
                    labelNilaiBaru.innerHTML = 'Tanggal Peresmian Baru <span class="text-danger">*</span>';
                    nilaiBaruInput.type = 'date';
                    nilaiBaruInput.placeholder = '';
                    nilaiBaruInput.classList.remove('d-none');
                    nilaiBaruInput.disabled = false;
                    nilaiBaruInput.required = true;
                    nilaiBaruInput.name = 'nilai_baru';
                } else if (val === 'Jumlah Pegawai') {
                    labelNilaiBaru.innerHTML = 'Jumlah Pegawai / Pengurus Baru <span class="text-danger">*</span>';
                    nilaiBaruInput.type = 'number';
                    nilaiBaruInput.min = '0';
                    nilaiBaruInput.placeholder = 'Contoh: 15';
                    nilaiBaruInput.classList.remove('d-none');
                    nilaiBaruInput.disabled = false;
                    nilaiBaruInput.required = true;
                    nilaiBaruInput.name = 'nilai_baru';
                } else if (val === 'Link Google Maps') {
                    labelNilaiBaru.innerHTML = 'Link Google Maps / Lokasi Baru <span class="text-danger">*</span>';
                    nilaiBaruInput.type = 'url';
                    nilaiBaruInput.placeholder = 'https://maps.app.goo.gl/... atau https://www.google.com/maps/...';
                    nilaiBaruInput.classList.remove('d-none');
                    nilaiBaruInput.disabled = false;
                    nilaiBaruInput.required = true;
                    nilaiBaruInput.name = 'nilai_baru';
                } else if (!val) {
                    nilaiBaruInput.type = 'text';
                    nilaiBaruInput.placeholder = 'Pilih data yang ingin diubah terlebih dahulu...';
                    nilaiBaruInput.classList.remove('d-none');
                    nilaiBaruInput.disabled = true;
                    nilaiBaruInput.required = false;
                } else {
                    nilaiBaruInput.type = 'text';
                    nilaiBaruInput.placeholder = 'Masukkan nilai usulan baru';
                    nilaiBaruInput.classList.remove('d-none');
                    nilaiBaruInput.disabled = false;
                    nilaiBaruInput.required = true;
                    nilaiBaruInput.name = 'nilai_baru';
                }
            }

            if (fieldPerubahan) {
                fieldPerubahan.addEventListener('change', updateNilaiBaruInput);
            }

            if (nilaiBaruFile) {
                nilaiBaruFile.addEventListener('change', function (e) {
                    if (e.target.files && e.target.files[0]) {
                        previewFotoImg.src = URL.createObjectURL(e.target.files[0]);
                        previewFotoBox.classList.remove('d-none');
                    } else {
                        previewFotoBox.classList.add('d-none');
                    }
                });
            }

            jenisPerubahan.addEventListener('change', toggleSections);
            jenisPenambahan.addEventListener('change', toggleSections);
            toggleSections();

            // SASARAN AUTOCOMPLETE LOGIC
            const existingSasaranList = @json($sasaranList ?? []);
            const sasaranDropdown = document.getElementById('sasaranDropdown');
            const sasaranInfoNotice = document.getElementById('sasaranInfoNotice');

            function checkMatchNotice(val) {
                const queryLower = val.trim().toLowerCase();
                const matched = existingSasaranList.find(s => s.nama_sasaran.toLowerCase() === queryLower);
                if (matched && sasaranInfoNotice) {
                    sasaranInfoNotice.classList.remove('d-none');
                } else if (sasaranInfoNotice) {
                    sasaranInfoNotice.classList.add('d-none');
                }
            }

            function renderSasaranDropdown(query) {
                if (!sasaranDropdown) return;
                const queryLower = query.trim().toLowerCase();
                if (!queryLower) {
                    sasaranDropdown.classList.add('d-none');
                    sasaranDropdown.innerHTML = '';
                    return;
                }

                const matches = existingSasaranList.filter(s => s.nama_sasaran.toLowerCase().includes(queryLower));

                if (matches.length === 0) {
                    sasaranDropdown.classList.add('d-none');
                    sasaranDropdown.innerHTML = '';
                    return;
                }

                let html = '<div class="px-3 py-2 bg-light border-bottom text-muted fw-bold small"><i class="bi bi-search me-1"></i> Data Sasaran Yang Sudah Ada:</div>';
                matches.forEach(item => {
                    const safeName = item.nama_sasaran.replace(/"/g, '&quot;');
                    html += `
                        <button type="button" class="dropdown-item py-2 px-3 border-bottom d-flex justify-content-between align-items-center sasaran-item-option text-wrap"
                            data-name="${safeName}" data-capacity="${item.kapasitas}">
                            <div>
                                <strong class="text-dark d-block mb-0">${item.nama_sasaran}</strong>
                                <small class="text-muted"><i class="bi bi-people me-1"></i> Kapasitas saat ini</small>
                            </div>
                            <span class="badge bg-primary rounded-pill px-3 py-1 ms-2 fs-6">${item.kapasitas} Orang</span>
                        </button>
                    `;
                });

                sasaranDropdown.innerHTML = html;
                sasaranDropdown.classList.remove('d-none');

                // Attach click handlers
                sasaranDropdown.querySelectorAll('.sasaran-item-option').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const selectedName = this.getAttribute('data-name');
                        const selectedCapacity = this.getAttribute('data-capacity');

                        namaSasaran.value = selectedName;
                        kapasitasSasaran.value = selectedCapacity;
                        sasaranDropdown.classList.add('d-none');

                        checkMatchNotice(selectedName);
                    });
                });
            }

            if (namaSasaran) {
                namaSasaran.addEventListener('input', function () {
                    renderSasaranDropdown(this.value);
                    checkMatchNotice(this.value);
                });

                namaSasaran.addEventListener('focus', function () {
                    if (this.value) {
                        renderSasaranDropdown(this.value);
                    }
                });

                if (namaSasaran.value) {
                    checkMatchNotice(namaSasaran.value);
                }
            }

            document.addEventListener('click', function (e) {
                if (sasaranDropdown && !sasaranDropdown.contains(e.target) && e.target !== namaSasaran) {
                    sasaranDropdown.classList.add('d-none');
                }
            });
        });
    </script>
@endsection