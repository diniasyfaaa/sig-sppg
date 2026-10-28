<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sppg;
use App\Models\Desa;
use Illuminate\Support\Facades\Storage;


class SppgController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $desa = Desa::orderBy('nama_desa')->get();
        $query = Sppg::with([
            'desa',
            'sasaran'
        ]);

        if ($request->filled('desa')) {
            $query->where('desa_id', $request->desa);
        }
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                // Kolom yang berada di tabel sppg
                $q->where('nama_sppg', 'LIKE', "%{$search}%")
                    ->orWhere('alamat', 'LIKE', "%{$search}%")
                    ->orWhere('ketua_sppg', 'LIKE', "%{$search}%")
                    ->orWhere('mitra_yayasan', 'LIKE', "%{$search}%")
                    ->orWhere('jumlah_pegawai', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->orWhere('tanggal_peresmian', 'LIKE', "%{$search}%")

                    ->orWhereHas('desa', function ($desaQuery) use ($search) {
                        $desaQuery->where(
                            'nama_desa',
                            'LIKE',
                            "%{$search}%"
                        );
                    })

                    ->orWhereHas('sasaran', function ($sasaranQuery) use ($search) {
                        $sasaranQuery
                            ->where('nama_sasaran', 'LIKE', "%{$search}%")
                            ->orWhere('kapasitas', 'LIKE', "%{$search}%");
                    });
            });
        }

        $sppg = $query
            ->latest()
            ->get();

        return view('admin.sppg.index', compact(
            'sppg',
            'desa'
        ));
    }

    /**
     * Display a listing of user resources.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function userIndex(Request $request)
    {
        $desa = Desa::orderBy('nama_desa')->get();
        $query = Sppg::with([
            'desa',
            'sasaran'
        ]);

        if ($request->filled('desa')) {
            $query->where('desa_id', $request->desa);
        }
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {

                $q->where('nama_sppg', 'LIKE', "%{$search}%")
                    ->orWhere('alamat', 'LIKE', "%{$search}%")
                    ->orWhere('ketua_sppg', 'LIKE', "%{$search}%")
                    ->orWhere('mitra_yayasan', 'LIKE', "%{$search}%")
                    ->orWhere('jumlah_pegawai', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->orWhere('tanggal_peresmian', 'LIKE', "%{$search}%")

                    ->orWhereHas('desa', function ($desaQuery) use ($search) {
                        $desaQuery->where(
                            'nama_desa',
                            'LIKE',
                            "%{$search}%"
                        );
                    })

                    ->orWhereHas('sasaran', function ($sasaranQuery) use ($search) {
                        $sasaranQuery
                            ->where('nama_sasaran', 'LIKE', "%{$search}%")
                            ->orWhere('kapasitas', 'LIKE', "%{$search}%");
                    });
            });
        }

        $sppg = $query
            ->latest()
            ->get();

        return view('user.sppg.index', compact(
            'sppg',
            'desa'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $desa = Desa::all();
        return view('admin.sppg.create', compact('desa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sppg' => 'required|string|max:255',
            'desa_id' => 'required|exists:desa,id',
            'alamat' => 'nullable|string',
            'tanggal_peresmian' => 'required|date',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'ketua_sppg' => 'nullable|string|max:255',
            'mitra_yayasan' => 'nullable|string|max:255',
            'jumlah_pegawai' => 'nullable|integer|min:0',
            'link_google_maps' => 'nullable|string|max:1000',

            'foto_depan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_dapur' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_belakang' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama_sppg.required' => 'Nama SPPG wajib diisi.',
            'desa_id.required' => 'Silakan pilih desa.',
            'tanggal_peresmian.required' => 'Tanggal peresmian wajib diisi.',
            'status.required' => 'Silakan pilih status.',
        ]);

        if ($request->hasFile('foto_depan')) {
            $validated['foto_depan'] = $request
                ->file('foto_depan')
                ->store('sppg/foto-depan', 'public');
        }

        if ($request->hasFile('foto_dapur')) {
            $validated['foto_dapur'] = $request
                ->file('foto_dapur')
                ->store('sppg/foto-dapur', 'public');
        }

        if ($request->hasFile('foto_belakang')) {
            $validated['foto_belakang'] = $request
                ->file('foto_belakang')
                ->store('sppg/foto-belakang', 'public');
        }

        Sppg::create($validated);

        return redirect()
            ->route('sppg.index')
            ->with('success', 'Data SPPG berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $sppg = Sppg::with([
            'desa',
            'sasaran',
            'menuHarian' => function ($query) {
                $query->where('status', 'disetujui')
                      ->orderBy('siklus_ke')
                      ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')");
            }
        ])->findOrFail($id);

        $menuHarianGrouped = $sppg->menuHarian->groupBy('siklus_ke');

        return view('admin.sppg.show', compact('sppg', 'menuHarianGrouped'));
    }

    /**
     * Display the specified user resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function userShow($id)
    {
        $sppg = Sppg::with([
            'desa',
            'sasaran',
            'menuHarian' => function ($query) {
                $query->where('status', 'disetujui')
                      ->orderBy('siklus_ke')
                      ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')");
            }
        ])->findOrFail($id);

        $menuHarianGrouped = $sppg->menuHarian->groupBy('siklus_ke');

        return view(
            'user.sppg.show',
            compact('sppg', 'menuHarianGrouped')
        );
    }

    /**
     * Display the specified resource for Kepala SPPG.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function kepalaShow($id)
    {
        $sppg = Sppg::with([
            'desa',
            'sasaran',
            'menuHarian' => function ($query) {
                $query->where('status', 'disetujui')
                      ->orderBy('siklus_ke')
                      ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')");
            }
        ])->findOrFail($id);

        $menuHarianGrouped = $sppg->menuHarian->groupBy('siklus_ke');

        return view('kepala-sppg.sppg.show', compact('sppg', 'menuHarianGrouped'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $sppg = \App\Models\Sppg::findOrFail($id);
        $desa = \App\Models\Desa::all();
        return view('admin.sppg.edit', compact('sppg', 'desa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_sppg' => 'required|string|max:255',
            'desa_id' => 'required|exists:desa,id',
            'alamat' => 'nullable|string',
            'tanggal_peresmian' => 'required|date',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'ketua_sppg' => 'nullable|string|max:255',
            'mitra_yayasan' => 'nullable|string|max:255',
            'jumlah_pegawai' => 'nullable|integer|min:0',
            'link_google_maps' => 'nullable|string|max:1000',

            'foto_depan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_dapur' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_belakang' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama_sppg.required' => 'Nama SPPG wajib diisi.',
            'desa_id.required' => 'Silakan pilih desa.',
            'tanggal_peresmian.required' => 'Tanggal peresmian wajib diisi.',
            'status.required' => 'Silakan pilih status.',
        ]);

        $sppg = Sppg::findOrFail($id);
        [$latitude, $longitude] = $this->getCoordinateFromGoogleMaps(
        $request->link_google_maps
    );

        $data = [
            'nama_sppg' => $request->nama_sppg,
            'desa_id' => $request->desa_id,
            'alamat' => $request->alamat,
            'tanggal_peresmian' => $request->tanggal_peresmian,
            'ketua_sppg' => $request->ketua_sppg,
            'mitra_yayasan' => $request->mitra_yayasan,
            'jumlah_pegawai' => $request->jumlah_pegawai,
            'status' => $request->status,
            'link_google_maps' => $request->link_google_maps,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];

        if($request->hasFile('foto_depan')){
        if($sppg->foto_depan){
            Storage::disk('public')->delete($sppg->foto_depan);
        }

        $data['foto_depan'] =
        $request->file('foto_depan')
                ->store('foto_sppg','public');

    }

        if($request->hasFile('foto_dapur')){
        if($sppg->foto_dapur){
            Storage::disk('public')->delete($sppg->foto_dapur);
        }

        $data['foto_dapur'] =
        $request->file('foto_dapur')
                ->store('foto_sppg','public');

    }

        if($request->hasFile('foto_belakang')){
        if($sppg->foto_belakang){
            Storage::disk('public')->delete($sppg->foto_belakang);
        }

        $data['foto_belakang'] =
        $request->file('foto_belakang')
                ->store('foto_sppg','public');

    }

        $sppg->update($data);
        return redirect()
            ->route('sppg.index')
            ->with('success', 'Data SPPG berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $sppg = Sppg::findOrFail($id);

        // Hapus semua data sasaran yang terkait
        $sppg->sasaran()->delete();

        // Hapus file foto dari storage
        if ($sppg->foto_depan) {
            Storage::disk('public')->delete($sppg->foto_depan);
        }

        if ($sppg->foto_dapur) {
            Storage::disk('public')->delete($sppg->foto_dapur);
        }

        if ($sppg->foto_belakang) {
            Storage::disk('public')->delete($sppg->foto_belakang);
        }

        // Hapus data SPPG
        $sppg->delete();

        return redirect()
            ->route('sppg.index')
            ->with('success', 'Data SPPG beserta seluruh data sasarannya berhasil dihapus.');
    }
}
