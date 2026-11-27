<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IzinSakit extends Model
{
    use HasFactory;

    protected $table = 'izin_sakits';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'jenis',
        'keterangan',
        'bukti_file',
        'status_approve',
    ];

    /**
     * Relasi ke model Siswa (untuk mengambil nama & kelas siswa)
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}