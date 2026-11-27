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

            $table->date('tanggal');

            $table->enum('jenis',[
                'izin',
                'sakit'
            ]);

            $table->text('keterangan');

            $table->string('bukti_file')->nullable();

            $table->enum('status_approve',[
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