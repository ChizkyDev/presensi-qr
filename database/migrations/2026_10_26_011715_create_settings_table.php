<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Masukkan data default awal untuk General & Security settings
        DB::table('settings')->insert([
            // General Settings
            ['key' => 'school_name', 'value' => 'SMA Negeri 1 Jakarta', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'school_npsn', 'value' => '20100001', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'school_timezone', 'value' => 'WIB (UTC+7)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'academic_year', 'value' => '2025 / 2026', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'academic_semester', 'value' => 'Semester 2', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'qr_duration', 'value' => '30', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'late_threshold', 'value' => '15', 'created_at' => now(), 'updated_at' => now()],

            // Security Settings (Tambahan)
            ['key' => 'security_location_verification', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'security_prevent_duplicate_scans', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'security_session_auto_expiry', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'security_admin_2fa', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};