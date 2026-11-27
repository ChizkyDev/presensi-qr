<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar semua jurusan
        $jurusans = ['RPL', 'TKJ', 'DKV', 'MKT', 'ATPH', 'APHP', 'TPM', 'TKR', 'TBKR', 'TL'];
        $tingkats = ['10', '11', '12'];

        $dataKelas = [];

        foreach ($tingkats as $tingkat) {
            foreach ($jurusans as $jurusan) {
                // Tentukan nomor kelas (default 1 dan 2)
                $nomorKelasList = [1, 2];

                // Pengecualian khusus: kelas 12 RPL hanya ada nomor kelas 1
                if ($tingkat === '12' && $jurusan === 'RPL') {
                    $nomorKelasList = [1];
                }

                foreach ($nomorKelasList as $nomor) {
                    $dataKelas[] = [
                        'kelas'       => $tingkat,
                        'jurusan'     => $jurusan,
                        'nomor_kelas' => $nomor,
                    ];
                }
            }
        }

        // Masukkan ke database tanpa duplikat
        foreach ($dataKelas as $k) {
            Kelas::firstOrCreate($k);
        }
    }
}