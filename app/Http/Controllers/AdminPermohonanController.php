<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanUpdateSppg;
use App\Models\Sppg;
use App\Models\Sasaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminPermohonanController extends Controller
{
    private function getCoordinateFromGoogleMaps($url)
    {
        if (!$url) {
            return [null, null];
        }

        $url = trim($url);

        // Jika URL berupa short link (maps.app.goo.gl / goo.gl/maps), ikuti redirect HTTP untuk mendapatkan URL lengkap
        if (str_contains($url, 'maps.app.goo.gl') || str_contains($url, 'goo.gl/maps') || str_contains($url, 'page.link')) {
            try {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 4);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
                curl_exec($ch);
                $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                curl_close($ch);

                if ($effectiveUrl) {
                    $url = $effectiveUrl;
                }
            } catch (\Throwable $e) {
                // Ignore curl error if offline, fallback to original url
            }
        }

        // Pattern 1: @latitude,longitude
        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $match)) {
            return [$match[1], $match[2]];
        }

        // Pattern 2: ?q=latitude,longitude atau &q=latitude,longitude
        if (preg_match('/[?&]q=(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $match)) {
            return [$match[1], $match[2]];
        }

        // Pattern 3: !3dlatitude!4dlongitude (dari format embed / place)
        if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $url, $match)) {
            return [$match[1], $match[2]];
        }

        // Pattern 4: center=latitude,longitude atau ll=latitude,longitude
        if (preg_match('/(center|ll)=(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $match)) {
            return [$match[2], $match[3]];
        }

        return [null, null];
    }

    /**
     * List permohonan update SPPG untuk admin.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');

        /** @var \Illuminate\Pagination\LengthAwarePaginator $permohonan */
        $permohonan = PermohonanUpdateSppg::with(['user', 'sppg.desa'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                return $query->whereHas('sppg', function ($q) use ($search) {
                    $q->where('nama_sppg', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalCount = PermohonanUpdateSppg::count();
        $pendingCount = PermohonanUpdateSppg::where('status', 'pending')->count();
        $approvedCount = PermohonanUpdateSppg::where('status', 'disetujui')->count();
        $rejectedCount = PermohonanUpdateSppg::where('status', 'ditolak')->count();

        return view('admin.permohonan.index', compact(
            'permohonan',
            'status',
            'search',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Detail permohonan.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $permohonan = PermohonanUpdateSppg::with(['user', 'sppg.desa', 'sppg.sasaran'])
            ->findOrFail($id);

        return view('admin.permohonan.show', compact('permohonan'));
    }

    /**
     * Approve / Setujui permohonan.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, $id)
    {
        $permohonan = PermohonanUpdateSppg::with('sppg')->findOrFail($id);

        if ($permohonan->status !== 'pending') {
            return back()->with('error', 'Permohonan ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($permohonan) {
            $sppg = $permohonan->sppg;

            if ($permohonan->jenis === 'perubahan_data') {
                if ($sppg) {
                    $fieldMap = [
                        'Nama SPPG' => 'nama_sppg',
                        'Desa' => 'desa_id',
                        'Alamat Lengkap' => 'alamat',
                        'Nama Ketua SPPG' => 'ketua_sppg',
                        'Mitra / Yayasan' => 'mitra_yayasan',
                        'Jumlah Pegawai' => 'jumlah_pegawai',
                        'Status Operasional' => 'status',
                        'Tanggal Peresmian' => 'tanggal_peresmian',
                        'Link Google Maps' => 'link_google_maps',
                        'Foto Depan' => 'foto_depan',
                        'Foto Dapur' => 'foto_dapur',
                        'Foto Belakang' => 'foto_belakang',
                    ];

                    if (isset($fieldMap[$permohonan->field_perubahan])) {
                        $col = $fieldMap[$permohonan->field_perubahan];

                        // Delete old photo if updating photo
                        if (in_array($col, ['foto_depan', 'foto_dapur', 'foto_belakang']) && $sppg->$col) {
                            Storage::disk('public')->delete($sppg->$col);
                        }

                        $sppg->$col = $permohonan->nilai_baru;

                        if ($col === 'link_google_maps') {
                            [$lat, $lng] = $this->getCoordinateFromGoogleMaps($permohonan->nilai_baru);
                            if ($lat && $lng) {
                                $sppg->latitude = $lat;
                                $sppg->longitude = $lng;
                            }
                        }

                        $sppg->save();
                    }
                }
            } elseif ($permohonan->jenis === 'penambahan_sasaran') {
                if ($sppg && $permohonan->nama_sasaran && $permohonan->kapasitas_sasaran) {
                    $existingSasaran = Sasaran::where('sppg_id', $sppg->id)
                        ->whereRaw('LOWER(nama_sasaran) = ?', [mb_strtolower(trim($permohonan->nama_sasaran))])
                        ->first();

                    if ($existingSasaran) {
                        $existingSasaran->kapasitas = $permohonan->kapasitas_sasaran;
                        $existingSasaran->save();
                    } else {
                        Sasaran::create([
                            'sppg_id' => $sppg->id,
                            'nama_sasaran' => $permohonan->nama_sasaran,
                            'kapasitas' => $permohonan->kapasitas_sasaran,
                        ]);
                    }

                    // Jika ada usulan perubahan jumlah pegawai
                    if ($permohonan->jumlah_pegawai_baru) {
                        $sppg->jumlah_pegawai = $permohonan->jumlah_pegawai_baru;
                        $sppg->save();
                    }
                }
            }

            $permohonan->update([
                'status' => 'disetujui',
                'is_read_kepala' => false,
                'approved_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.permohonan.show', $id)
            ->with('success', 'Permohonan berhasil DISETUJUI dan data SPPG telah diperbarui secara otomatis!');
    }

    /**
     * Reject / Tolak permohonan dengan alasan wajib.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $permohonan = PermohonanUpdateSppg::findOrFail($id);

        if ($permohonan->status !== 'pending') {
            return back()->with('error', 'Permohonan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'alasan_penolakan' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $permohonan->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
            'is_read_kepala' => false,
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('admin.permohonan.show', $id)
            ->with('success', 'Permohonan telah DITOLAK. Alasan penolakan telah dikirimkan ke Kepala SPPG.');
    }
}
