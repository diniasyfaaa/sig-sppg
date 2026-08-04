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
        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $match)) {
            return [$match[1], $match[2]];
        }
        if (preg_match('/q=(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $match)) {
            return [$match[1], $match[2]];
        }
        if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $url, $match)) {
            return [$match[1], $match[2]];
        }
        return [null, null];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sppg = Sppg::with([
            'desa',
            'sasaran'
        ])->findOrFail($id);

        return view('admin.sppg.show', compact('sppg'));
    }

    public function userShow($id)
    {
        $sppg = Sppg::with([
            'desa',
            'sasaran'
        ])->findOrFail($id);

        return view(
            'user.sppg.show',
            compact('sppg')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
