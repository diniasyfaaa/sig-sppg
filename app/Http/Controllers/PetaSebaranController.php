<?php

namespace App\Http\Controllers;

use App\Models\Sppg;

class PetaSebaranController extends Controller
{
    public function index()
    {
        return view('admin.peta', [
            'sppg' => $this->getPetaData(false)
        ]);
    }
    public function userIndex()
    {
        return view('user.peta', [
            'sppg' => $this->getPetaData(true)
        ]);
    }
    private function getPetaData(bool $isUser = false)
    {
        return Sppg::with('desa')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('nama_sppg')
            ->get()
            ->map(function ($item) use ($isUser) {
                return [
                    'id' => $item->id,
                    'nama_sppg' => $item->nama_sppg,
                    'ketua_sppg' => $item->ketua_sppg,
                    'mitra_yayasan' => $item->mitra_yayasan,
                    'alamat' => $item->alamat,
                    'status' => $item->status,
                    'latitude' => (float) $item->latitude,
                    'longitude' => (float) $item->longitude,
                    'nama_desa' => optional($item->desa)->nama_desa ?? '-',
                    'detail_url' => $isUser
                        ? route('user.sppg.show', $item->id)
                        : route('sppg.show', $item->id),
                ];
            })
            ->values();
    }
}