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
        'tanggal_selesai',
        'jumlah_hari',
        'jenis',
        'keterangan',
        'bukti_file',
        'kontak_darurat',
        'hubungan_kontak',
        'status_approve',
    ];

    /**
     * Relasi ke model Siswa (untuk mengambil nama & kelas siswa)
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Kode pengajuan untuk ditampilkan, contoh: LR-003
     */
    public function getKodeAttribute(): string
    {
        return 'LR-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Label jenis izin untuk ditampilkan.
     */
    public function getJenisLabelAttribute(): string
    {
        return [
            'sakit'   => 'Sick Leave',
            'izin'    => 'Personal Reason',
            'darurat' => 'Emergency',
        ][$this->jenis] ?? ucfirst((string) $this->jenis);
    }
}