<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiPresensi extends Model
{
    use HasFactory;

    protected $table = 'sesi_presensi';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mapel',
        'qr_token',
        'expired_at',
        'status',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'sesi_presensi_id');
    }
}