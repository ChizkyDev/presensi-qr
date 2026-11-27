<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'kelas';

    // Kolom yang dapat diisi melalui form/store
    protected $fillable = [
        'kelas',
        'jurusan',
        'nomor_kelas',
    ];

    // Relasi One-to-Many ke model Siswa (untuk menghitung jumlah siswa per kelas)
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    // Accessor / Helper untuk menggabungkan nama kelas (misal: "12 RPL 1")
    public function getNamaLengkapAttribute()
    {
        return "{$this->kelas} {$this->jurusan} {$this->nomor_kelas}";
    }

    // Accessor / Helper untuk membuat kode kelas otomatis (misal: "C-001")
    public function getKodeKelasAttribute()
    {
        return 'C-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }
}