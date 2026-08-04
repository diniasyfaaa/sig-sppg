<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sasaran;

class Sppg extends Model
{
    use HasFactory;

    protected $table = 'sppg';

    protected $fillable = [
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

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function sasaran()
    {
        return $this->hasMany(Sasaran::class, 'sppg_id');
    }
        public function getBadgeStatusAttribute()
    {
        return $this->status == 'Aktif'
            ? 'success'
            : 'danger';
    }
}