<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sppg;
use App\Models\PermohonanUpdateSppg;
use App\Models\Sasaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KepalaController extends Controller
{
    /**
     * Dashboard Kepala SPPG.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $sppg = $user->sppg;

        if ($sppg) {
            $sppg->load(['desa', 'sasaran']);
        }

        $permohonanCount = PermohonanUpdateSppg::where('user_id', $user->id)->count();
        $pendingCount    = PermohonanUpdateSppg::where('user_id', $user->id)->where('status', 'pending')->count();
        $approvedCount   = PermohonanUpdateSppg::where('user_id', $user->id)->where('status', 'disetujui')->count();
        $rejectedCount   = PermohonanUpdateSppg::where('user_id', $user->id)->where('status', 'ditolak')->count();

        $recentPermohonan = PermohonanUpdateSppg::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('kepala-sppg.dashboard', compact(
            'user',
            'sppg',
            'permohonanCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'recentPermohonan'
        ));
    }

    /**
     * List permohonan oleh Kepala SPPG.
     */
    public function permohonanIndex(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status');

        // Mark unread status update notifications as read for Kepala SPPG
        PermohonanUpdateSppg::where('user_id', $user->id)
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->where('is_read_kepala', false)
            ->update(['is_read_kepala' => true]);

        $permohonanCount = PermohonanUpdateSppg::where('user_id', $user->id)->count();
        $pendingCount    = PermohonanUpdateSppg::where('user_id', $user->id)->where('status', 'pending')->count();
        $approvedCount   = PermohonanUpdateSppg::where('user_id', $user->id)->where('status', 'disetujui')->count();
        $rejectedCount   = PermohonanUpdateSppg::where('user_id', $user->id)->where('status', 'ditolak')->count();

        $search = $request->get('search');

        $permohonan = PermohonanUpdateSppg::where('user_id', $user->id)
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                      ->orWhere('field_perubahan', 'like', "%{$search}%")
                      ->orWhere('nama_sasaran', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('kepala-sppg.permohonan.index', compact(
            'permohonan',
            'status',
            'permohonanCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Form buat permohonan baru.
     */
    public function permohonanCreate()
    {
        $user = Auth::user();
        $sppg = $user->sppg;

        if (!$sppg) {
            return redirect()
                ->route('kepala.dashboard')
                ->with('error', 'Akun Anda belum ditautkan ke unit SPPG manapun. Silakan hubungi Administrator.');
        }

        $desa = \App\Models\Desa::all();
        $sasaranList = Sasaran::where('sppg_id', $sppg->id)->orderBy('nama_sasaran')->get(['id', 'nama_sasaran', 'kapasitas']);

        return view('kepala-sppg.permohonan.create', compact('sppg', 'desa', 'sasaranList'));
    }

    /**
     * Simpan permohonan baru.
     */
    public function permohonanStore(Request $request)
    {
        $user = Auth::user();
        $sppg = $user->sppg;

        if (!$sppg) {
            return redirect()
                ->route('kepala.dashboard')
                ->with('error', 'Akun Anda belum ditautkan ke unit SPPG.');
        }

        $rules = [
            'jenis'         => 'required|in:perubahan_data,penambahan_sasaran',
            'deskripsi'     => 'nullable|string',
            'foto_landasan' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5048',
        ];

        $messages = [
            'jenis.required'         => 'Pilih jenis permohonan.',
            'jenis.in'               => 'Jenis permohonan tidak valid.',
            'foto_landasan.required' => 'Upload foto/dokumen landasan perubahan wajib diisi.',
            'foto_landasan.mimes'    => 'Format file landasan perubahan harus berupa JPG, PNG, atau PDF.',
            'foto_landasan.max'      => 'Ukuran file landasan perubahan maksimal 5 MB.',
        ];

        if ($request->jenis === 'perubahan_data') {
            $rules['field_perubahan'] = 'required|string|in:Nama SPPG,Desa,Alamat Lengkap,Nama Ketua SPPG,Mitra / Yayasan,Jumlah Pegawai,Status Operasional,Tanggal Peresmian,Link Google Maps,Foto Depan,Foto Dapur,Foto Belakang';
            $messages['field_perubahan.required'] = 'Pilih data yang ingin diubah.';
            $messages['field_perubahan.in']       = 'Pilihan data yang ingin diubah tidak valid.';

            $field = $request->field_perubahan;

            if (in_array($field, ['Foto Depan', 'Foto Dapur', 'Foto Belakang'])) {
                $rules['nilai_baru_file'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
                $messages['nilai_baru_file.required'] = 'Upload foto baru wajib diisi.';
                $messages['nilai_baru_file.image']    = 'File harus berupa gambar.';
                $messages['nilai_baru_file.mimes']    = 'Format foto harus berupa JPG, JPEG, atau PNG.';
                $messages['nilai_baru_file.max']      = 'Ukuran foto maksimal 2 MB.';
            } elseif ($field === 'Desa') {
                $rules['nilai_baru'] = 'required|exists:desa,id';
                $messages['nilai_baru.required'] = 'Pilih wilayah desa baru.';
                $messages['nilai_baru.exists']   = 'Desa pilihan tidak valid.';
            } elseif ($field === 'Status Operasional') {
                $rules['nilai_baru'] = 'required|in:Aktif,Tidak Aktif';
                $messages['nilai_baru.required'] = 'Pilih status operasional baru.';
                $messages['nilai_baru.in']       = 'Status harus berupa Aktif atau Tidak Aktif.';
            } elseif ($field === 'Jumlah Pegawai') {
                $rules['nilai_baru'] = 'required|integer|min:0';
                $messages['nilai_baru.required'] = 'Jumlah pegawai wajib diisi.';
                $messages['nilai_baru.integer']  = 'Jumlah pegawai harus angka.';
                $messages['nilai_baru.min']      = 'Jumlah pegawai minimal 0.';
            } elseif ($field === 'Tanggal Peresmian') {
                $rules['nilai_baru'] = 'required|date';
                $messages['nilai_baru.required'] = 'Tanggal peresmian wajib diisi.';
                $messages['nilai_baru.date']     = 'Format tanggal peresmian tidak valid.';
            } elseif ($field === 'Link Google Maps') {
                $rules['nilai_baru'] = 'required|string|max:1000';
                $messages['nilai_baru.required'] = 'Link Google Maps wajib diisi.';
                $messages['nilai_baru.max']      = 'Link Google Maps maksimal 1000 karakter.';
            } else {
                $rules['nilai_baru'] = 'required|string|max:255';
                $messages['nilai_baru.required'] = 'Data / Nilai usulan baru wajib diisi.';
                $messages['nilai_baru.max']      = 'Usulan nilai baru maksimal 255 karakter.';
            }
        } else {
            $rules['nama_sasaran']       = 'required|string|max:255';
            $rules['kapasitas_sasaran']  = 'required|integer|min:1';
            $rules['jumlah_pegawai_baru'] = 'nullable|integer|min:0';
            $messages['nama_sasaran.required']      = 'Nama sasaran baru wajib diisi.';
            $messages['kapasitas_sasaran.required'] = 'Kapasitas sasaran wajib diisi.';
            $messages['kapasitas_sasaran.min']      = 'Kapasitas minimal 1.';
        }

        $validated = $request->validate($rules, $messages);

        $nilaiBaru = $validated['nilai_baru'] ?? null;
        if ($request->jenis === 'perubahan_data' && in_array($request->field_perubahan, ['Foto Depan', 'Foto Dapur', 'Foto Belakang'])) {
            if ($request->hasFile('nilai_baru_file')) {
                $nilaiBaru = $request->file('nilai_baru_file')->store('foto_sppg', 'public');
            }
        }

        $fotoPath = null;
        if ($request->hasFile('foto_landasan')) {
            $fotoPath = $request->file('foto_landasan')->store('permohonan/landasan', 'public');
        }

        PermohonanUpdateSppg::create([
            'user_id'             => $user->id,
            'sppg_id'             => $sppg->id,
            'jenis'               => $validated['jenis'],
            'field_perubahan'     => $validated['field_perubahan'] ?? null,
            'nilai_baru'          => $nilaiBaru,
            'nama_sasaran'        => $validated['nama_sasaran'] ?? null,
            'kapasitas_sasaran'   => $validated['kapasitas_sasaran'] ?? null,
            'jumlah_pegawai_baru' => $validated['jumlah_pegawai_baru'] ?? null,
            'deskripsi'           => $validated['deskripsi'] ?? null,
            'foto_landasan'       => $fotoPath,
            'status'              => 'pending',
        ]);

        return redirect()
            ->route('kepala.permohonan.index')
            ->with('success', 'Permohonan update data berhasil diajukan! Menunggu peninjauan dari Administrator.');
    }

    /**
     * Detail permohonan.
     */
    public function permohonanShow($id)
    {
        $user = Auth::user();

        $permohonan = PermohonanUpdateSppg::with(['user', 'sppg.desa'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        if (!$permohonan->is_read_kepala) {
            $permohonan->update(['is_read_kepala' => true]);
        }

        return view('kepala-sppg.permohonan.show', compact('permohonan'));
    }

    /**
     * Data Sasaran SPPG untuk Kepala SPPG.
     */
    public function sasaranIndex(Request $request)
    {
        $user = Auth::user();
        $sppg = $user->sppg;
        $search = $request->get('search');

        $sasaran = collect();
        if ($sppg) {
            $sppg->load('desa');
            $sasaran = Sasaran::where('sppg_id', $sppg->id)
                ->when($search, function ($query, $search) {
                    return $query->where('nama_sasaran', 'like', "%{$search}%");
                })
                ->orderBy('nama_sasaran')
                ->get();
        }

        return view('kepala-sppg.sasaran.index', compact('user', 'sppg', 'sasaran', 'search'));
    }
}
