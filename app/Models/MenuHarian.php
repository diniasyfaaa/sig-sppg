<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

class MenuHarian extends Model
{
    use HasFactory;

    protected $table = 'menu_harians';

    protected $fillable = [
        'kode',
        'sppg_id',
        'siklus_ke',
        'hari',
        'menu_makanan',
        'standar_gizi',
        'foto',
        'status',
        'alasan_penolakan',
    ];

    /**
     * Boot model listeners for automatic storage file cleanup and code generation.
     */
    protected static function booted()
    {
        static::creating(function ($menu) {
            if (!$menu->kode) {
                $sppg = Sppg::find($menu->sppg_id);
                $sppgCode = $sppg ? str_replace('SPPG-', '', $sppg->kode ?: '0000') : '0000';
                $count = static::where('sppg_id', $menu->sppg_id)->count() + 1;
                $menu->kode = 'MNU-' . $sppgCode . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            }
        });
        static::updating(function ($menu) {
            if ($menu->isDirty('foto')) {
                $oldFoto = $menu->getOriginal('foto');
                if ($oldFoto) {
                    Storage::disk('public')->delete($oldFoto);
                }
            }
        });

        static::deleting(function ($menu) {
            if ($menu->foto) {
                Storage::disk('public')->delete($menu->foto);
            }
        });
    }

    public function sppg()
    {
        return $this->belongsTo(Sppg::class, 'sppg_id');
    }
}
