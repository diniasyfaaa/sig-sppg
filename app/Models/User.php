<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'sppg_id',
        'kode',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Boot model listeners for automatic unique code generation.
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (!$user->kode) {
                $user->kode = static::generateKode($user);
            }
        });
    }

    /**
     * Generate a unique kode for the given user instance.
     * Format for Kepala SPPG : USR-{kode_sppg}-{seq}
     * Format for Admin        : USR-ADM-{seq}
     */
    public static function generateKode($user): string
    {
        if ($user->role === 'kepala_sppg' && $user->sppg_id) {
            $sppg = Sppg::find($user->sppg_id);
            $sppgKode = $sppg ? $sppg->kode : 'SPPG';
            // 1 SPPG = 1 akun, kode langsung tanpa seq
            return 'USR-' . $sppgKode;
        }

        // Admin tetap pakai seq
        $prefix = 'USR-ADM';
        $count = static::where('kode', 'like', $prefix . '-%')->count() + 1;
        return $prefix . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);
    }

    public function sppg()
    {
        return $this->belongsTo(Sppg::class, 'sppg_id');
    }

    public function permohonan()
    {
        return $this->hasMany(PermohonanUpdateSppg::class, 'user_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKepalaSppg()
    {
        return $this->role === 'kepala_sppg';
    }
}
