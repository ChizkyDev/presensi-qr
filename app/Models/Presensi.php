<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensis'; // Sesuai tabel di database

    protected $fillable = [
        'sesi_presensi_id',
        'siswa_id',
        'tanggal',
        'jam_scan',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function sesiPresensi()
    {
        return $this->belongsTo(SesiPresensi::class, 'sesi_presensi_id');
    }
}