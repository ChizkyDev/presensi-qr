<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sesi_presensi_id')
                  ->constrained('sesi_presensi')
                  ->cascadeOnDelete();

            $table->foreignId('siswa_id')
                  ->constrained('siswas')
                  ->cascadeOnDelete();

            $table->date('tanggal');
            $table->time('jam_scan');

            $table->enum('status', [
                'hadir',
                'telat',
                'izin',
                'sakit',
                'alpha'
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};