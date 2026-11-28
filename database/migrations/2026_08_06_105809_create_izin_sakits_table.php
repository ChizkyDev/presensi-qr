<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('izin_sakits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('siswa_id')
                  ->constrained('siswas')
                  ->cascadeOnDelete();

            $table->date('tanggal');                                  // tanggal mulai
            $table->date('tanggal_selesai')->nullable();              // tanggal selesai
            $table->unsignedSmallInteger('jumlah_hari')->default(1);  // durasi (hari), dihitung server

            $table->enum('jenis', [
                'izin',      // Personal Reason
                'sakit',     // Sick Leave
                'darurat'    // Emergency
            ]);

            $table->text('keterangan');

            $table->string('bukti_file')->nullable();

            $table->string('kontak_darurat', 20)->nullable();
            $table->string('hubungan_kontak', 50)->nullable();

            $table->enum('status_approve', [
                'pending',
                'diterima',
                'ditolak'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('izin_sakits');
    }
};