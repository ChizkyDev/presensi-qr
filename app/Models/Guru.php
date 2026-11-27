<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus';

    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'subject',
        'homeroom',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi One-to-Many ke model SesiPresensi
     * (dipakai untuk menampilkan Subject dan Classes di User Management)
     */
    public function sesiPresensi(): HasMany
    {
        return $this->hasMany(SesiPresensi::class, 'guru_id');
    }
}