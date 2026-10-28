<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Sasaran;
use App\Models\Sppg;

class DashboardController extends Controller
{
    public function index()
    {
        return view(
            'admin.dashboard',
            $this->getDashboardData()
        );
    }
    public function userDashboard()
    {
        return view(
            'user.dashboard',
            $this->getDashboardData()
        );
    }
    public function statistik()
    {
        return view(
            'admin.statistik',
            $this->getStatistikData()
        );
    }
    public function userStatistik()
    {
        return view(
            'user.statistik',
            $this->getStatistikData()
        );
    }
    private function getDashboardData(): array
    {
        $totalDesa = Desa::count();
        $totalSppg = Sppg::count();
        $totalSasaran = Sasaran::count();
        $totalKapasitas = Sasaran::sum('kapasitas');
        $totalPegawai = Sppg::sum('jumlah_pegawai');

        $sppgTerbaru = Sppg::with('desa')
            ->latest()
            ->take(5)
            ->get();

        $sppg = Sppg::with('desa')->get();

        $dataSppgStatistik = Sppg::with('desa')
            ->withSum('sasaran', 'kapasitas')
            ->get();

        $statistikDesa = Desa::orderBy('nama_desa')
            ->get()
            ->map(function ($desa) use ($dataSppgStatistik) {
                $sppgPerDesa = $dataSppgStatistik->filter(
                    function ($item) use ($desa) {
                        return (int) $item->desa_id === (int) $desa->id;
                    }
                );

                return [
                    'nama_desa' => $desa->nama_desa,

                    'jumlah_sppg' => $sppgPerDesa->count(),

                    'total_kapasitas' => $sppgPerDesa->sum(
                        function ($item) {
                            return (int) (
                                $item->sasaran_sum_kapasitas ?? 0
                            );
                        }
                    ),

                    'total_pegawai' => $sppgPerDesa->sum(
                        function ($item) {
                            return (int) (
                                $item->jumlah_pegawai ?? 0
                            );
                        }
                    ),
                ];
            })
            ->values();

        $totalAktif = Sppg::where('status', 'Aktif')->count();

        $totalTidakAktif = Sppg::where(
            'status',
            'Tidak Aktif'
        )->count();

        return compact(
            'totalDesa',
            'totalSppg',
            'totalSasaran',
            'totalKapasitas',
            'totalPegawai',
            'sppgTerbaru',
            'sppg',
            'statistikDesa',
            'totalAktif',
            'totalTidakAktif'
        );
    }
    private function getStatistikData(): array
    {
        $desa = Desa::orderBy('nama_desa')->get();

        $sppg = Sppg::with('desa')
            ->withSum('sasaran', 'kapasitas')
            ->orderBy('nama_sppg')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,

                    'desa_id' => $item->desa_id,

                    'nama_desa' =>
                        optional($item->desa)->nama_desa ?? '-',

                    'nama_sppg' => $item->nama_sppg,

                    'jumlah_pegawai' =>
                        (int) ($item->jumlah_pegawai ?? 0),

                    'kapasitas' =>
                        (int) (
                            $item->sasaran_sum_kapasitas ?? 0
                        ),

                    'tanggal_peresmian' =>
                        $item->tanggal_peresmian,

                    'status' =>
                        $item->status ?? 'Aktif',
                ];
            })
            ->values();

        return compact(
            'desa',
            'sppg'
        );
    }
}