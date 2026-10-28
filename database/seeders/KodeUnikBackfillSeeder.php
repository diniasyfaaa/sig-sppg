<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Desa;
use App\Models\Sppg;
use App\Models\Sasaran;
use App\Models\PermohonanUpdateSppg;
use App\Models\MenuHarian;

class KodeUnikBackfillSeeder extends Seeder
{
    public function run()
    {
        // 1. Update Kode Pos Desa
        $postalMap = [
            'Cileunyi Kulon' => '40621',
            'Cileunyi Wetan' => '40622',
            'Cimekar'        => '40623',
            'Cinunuk'        => '40624',
            'Cibiru Wetan'   => '40625',
            'Cibiru Hilir'   => '40626',
        ];

        foreach (Desa::all() as $d) {
            $trimmedName = trim($d->nama_desa);
            if (isset($postalMap[$trimmedName])) {
                $d->kode_pos = $postalMap[$trimmedName];
            } else {
                $d->kode_pos = '406' . (21 + ($d->id % 6));
            }
            $d->save();
        }

        // 2. Backfill SPPG Codes
        $desaGroupedSppg = Sppg::orderBy('id')->get()->groupBy('desa_id');
        foreach ($desaGroupedSppg as $desaId => $sppgs) {
            $desa = Desa::find($desaId);
            $kodeDesa = ($desa && $desa->kode_pos) ? substr($desa->kode_pos, -2) : str_pad($desaId, 2, '0', STR_PAD_LEFT);
            $seq = 1;
            foreach ($sppgs as $sppg) {
                $sppg->kode = 'SPPG-' . $kodeDesa . str_pad($seq++, 2, '0', STR_PAD_LEFT);
                $sppg->save();
            }
        }

        // 3. Backfill Sasaran Codes
        $sppgGroupedSasaran = Sasaran::orderBy('id')->get()->groupBy('sppg_id');
        foreach ($sppgGroupedSasaran as $sppgId => $sasarans) {
            $sppg = Sppg::find($sppgId);
            $sppgCode = $sppg ? str_replace('SPPG-', '', $sppg->kode ?: '0000') : '0000';
            $seq = 1;
            foreach ($sasarans as $sasaran) {
                $sasaran->kode = 'SSR-' . $sppgCode . '-' . str_pad($seq++, 3, '0', STR_PAD_LEFT);
                $sasaran->save();
            }
        }

        // 4. Backfill Permohonan Codes
        $sppgGroupedPermohonan = PermohonanUpdateSppg::orderBy('id')->get()->groupBy('sppg_id');
        foreach ($sppgGroupedPermohonan as $sppgId => $permohonans) {
            $sppg = Sppg::find($sppgId);
            $sppgCode = $sppg ? str_replace('SPPG-', '', $sppg->kode ?: '0000') : '0000';
            $seq = 1;
            foreach ($permohonans as $p) {
                $p->kode = 'PMH-' . $sppgCode . '-' . str_pad($seq++, 3, '0', STR_PAD_LEFT);
                $p->save();
            }
        }

        // 5. Backfill Menu Harian Codes
        $sppgGroupedMenu = MenuHarian::orderBy('id')->get()->groupBy('sppg_id');
        foreach ($sppgGroupedMenu as $sppgId => $menus) {
            $sppg = Sppg::find($sppgId);
            $sppgCode = $sppg ? str_replace('SPPG-', '', $sppg->kode ?: '0000') : '0000';
            $seq = 1;
            foreach ($menus as $m) {
                $m->kode = 'MNU-' . $sppgCode . '-' . str_pad($seq++, 3, '0', STR_PAD_LEFT);
                $m->save();
            }
        }
    }
}
