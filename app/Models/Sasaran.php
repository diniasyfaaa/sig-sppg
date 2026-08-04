<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sasaran extends Model
{
    use HasFactory;

    protected $table = 'sasaran';

    protected $fillable = [
        'sppg_id',
        'nama_sasaran',
        'kapasitas'
    ];

    // Relasi ke SPPG
    public function sppg()
    {
        return $this->belongsTo(Sppg::class);
    }
}