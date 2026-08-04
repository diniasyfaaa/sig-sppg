<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Desa;

class DesaSeeder extends Seeder
{
    public function run()
    {
        $desa = [
            'Cileunyi Kulon',
            'Cileunyi Wetan',
            'Cinunuk',
            'Cimekar',
            'Cibiru Hilir',
            'Cibiru Wetan'
        ];

        foreach ($desa as $item) {
            Desa::create([
                'nama_desa' => $item
            ]);
        }
    }
}