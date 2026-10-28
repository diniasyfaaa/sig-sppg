<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

class PermohonanUpdateSppg extends Model
{
    use HasFactory;

    protected $table = 'permohonan_update_sppg';

    protected $fillable = [
        'kode',
        'user_id',
        'sppg_id',
        'jenis',
        'field_perubahan',
        'nilai_baru',
        'nama_sasaran',
        'kapasitas_sasaran',
        'jumlah_pegawai_baru',
        'deskripsi',
        'foto_landasan',
        'status',
        'alasan_penolakan',
        'is_read_kepala',
    ];

    protected $casts = [
        'is_read_kepala' => 'boolean',
    ];

    /**
     * Boot model listeners for automatic storage file cleanup and code generation.
     */
    protected static function booted()
    {
        static::creating(function ($permohonan) {
            if (!$permohonan->kode) {
                $sppg = Sppg::find($permohonan->sppg_id);
                $sppgCode = $sppg ? str_replace('SPPG-', '', $sppg->kode ?: '0000') : '0000';
                $count = static::where('sppg_id', $permohonan->sppg_id)->count() + 1;
                $permohonan->kode = 'PMH-' . $sppgCode . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            }
        });
        static::updating(function ($permohonan) {
            if ($permohonan->isDirty('foto_landasan')) {
                $oldFile = $permohonan->getOriginal('foto_landasan');
                if ($oldFile) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
            if ($permohonan->isDirty('nilai_baru') && $permohonan->field_perubahan && str_contains(strtolower($permohonan->field_perubahan), 'foto')) {
                $oldFile = $permohonan->getOriginal('nilai_baru');
                if ($oldFile) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
        });

        static::deleting(function ($permohonan) {
            if ($permohonan->foto_landasan) {
                Storage::disk('public')->delete($permohonan->foto_landasan);
            }
            if ($permohonan->nilai_baru && $permohonan->field_perubahan && str_contains(strtolower($permohonan->field_perubahan), 'foto')) {
                Storage::disk('public')->delete($permohonan->nilai_baru);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sppg()
    {
        return $this->belongsTo(Sppg::class);
    }
}
