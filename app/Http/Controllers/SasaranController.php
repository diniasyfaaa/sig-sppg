<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sppg;
use App\Models\Sasaran;

class SasaranController extends Controller
{
    public function index($id)
    {
        $sppg = Sppg::findOrFail($id);
        $sasaran = Sasaran::where('sppg_id', $id)->get();
        return view('admin.sasaran.index', compact('sppg', 'sasaran'));
    }
    public function userIndex($id)
    {
        $sppg = Sppg::findOrFail($id);
        $sasaran = Sasaran::where('sppg_id', $id)
            ->orderBy('nama_sasaran')
            ->get();
        return view('user.sasaran', compact('sppg', 'sasaran'));
    }
    public function create($id)
    {
        $sppg = Sppg::findOrFail($id);
        return view('admin.sasaran.create', compact('sppg'));
    }
    public function store(Request $request, $sppgId)
    {
        $validated = $request->validate(
            [
                'nama_sasaran' => 'required|string|max:255',
                'kapasitas' => 'required|integer|min:0',
            ],
            [
                'nama_sasaran.required' => 'Nama sasaran wajib diisi.',
                'nama_sasaran.string' => 'Nama sasaran harus berupa teks.',
                'nama_sasaran.max' => 'Nama sasaran maksimal 255 karakter.',

                'kapasitas.required' => 'Kapasitas wajib diisi.',
                'kapasitas.integer' => 'Kapasitas harus berupa angka.',
                'kapasitas.min' => 'Kapasitas tidak boleh kurang dari 0.',
            ]
        );

        $validated['sppg_id'] = $sppgId;

        Sasaran::create($validated);

        return redirect()
            ->route('sasaran.index', $sppgId)
            ->with('success', 'Data sasaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $sasaran = Sasaran::findOrFail($id);
        return view('admin.sasaran.edit', compact('sasaran'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_sasaran' => 'required|string|max:255',
            'kapasitas'    => 'required|numeric|min:0',
        ]);

        $sasaran = Sasaran::findOrFail($id);

        $sasaran->update([
            'nama_sasaran' => $request->nama_sasaran,
            'kapasitas'    => $request->kapasitas,
        ]);

        return redirect()
            ->route('sasaran.index', $sasaran->sppg_id)
            ->with('success', 'Data berhasil diupdate');
    }
    public function destroy($id)
    {
        $sasaran = Sasaran::findOrFail($id);
        $sppgId = $sasaran->sppg_id;
        $sasaran->delete();
        
        return redirect()
            ->route('sasaran.index', $sppgId)
            ->with('success', 'Data sasaran berhasil dihapus!');
    }
}