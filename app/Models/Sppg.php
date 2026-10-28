<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sasaran;

use Illuminate\Support\Facades\Storage;

class Sppg extends Model
{
    use HasFactory;

    protected $table = 'sppg';

    protected $fillable = [
        'kode',
        'desa_id',
        'nama_sppg',
        'alamat',
        'tanggal_peresmian',
        'ketua_sppg',
        'mitra_yayasan',
        'jumlah_pegawai',
        'status',
        'link_google_maps',
        'latitude',
        'longitude',
        'foto_depan',
        'foto_dapur',
        'foto_belakang'
    ];

    /**
     * Boot model listeners for automatic storage file cleanup and code generation.
     */
    protected static function booted()
    {
        static::creating(function ($sppg) {
            if (!$sppg->kode) {
                $desa = Desa::find($sppg->desa_id);
                $kodeDesa = $desa ? $desa->kode_dua_digit : '00';
                $count = static::where('desa_id', $sppg->desa_id)->count() + 1;
                $sppg->kode = 'SPPG-' . $kodeDesa . str_pad($count, 2, '0', STR_PAD_LEFT);
            }
        });
        static::updating(function ($sppg) {
            foreach (['foto_depan', 'foto_dapur', 'foto_belakang'] as $field) {
                if ($sppg->isDirty($field)) {
                    $oldFile = $sppg->getOriginal($field);
                    if ($oldFile) {
                        Storage::disk('public')->delete($oldFile);
                    }
                }
            }
        });

        static::deleting(function ($sppg) {
            foreach (['foto_depan', 'foto_dapur', 'foto_belakang'] as $field) {
                if ($sppg->$field) {
                    Storage::disk('public')->delete($sppg->$field);
                }
            }
        });
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function sasaran()
    {
        return $this->hasMany(Sasaran::class, 'sppg_id');
    }

    public function permohonan()
    {
        return $this->hasMany(PermohonanUpdateSppg::class, 'sppg_id');
    }

    public function kepala()
    {
        return $this->hasOne(User::class, 'sppg_id')->where('role', 'kepala_sppg');
    }

    public function menuHarian()
    {
        return $this->hasMany(MenuHarian::class, 'sppg_id');
    }

    public function getBadgeStatusAttribute()
    {
        return $this->status == 'Aktif'
            ? 'success'
            : 'danger';
    }
}