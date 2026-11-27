<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa; 
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaRegisterController extends Controller
{
    // Menampilkan halaman form pendaftaran siswa
    public function showSiswaRegisterForm()
    {
        // Ambil data kelas dari database
        $kelases = Kelas::all(); 

        // JIKA TABEL KOSONG: Berikan data dummy otomatis agar dropdown tetap muncul
        if ($kelases->isEmpty()) {
            $kelases = collect([
                (object)['id' => 1, 'kelas' => 'XII', 'jurusan' => 'RPL', 'nomor_kelas' => '1'],
                (object)['id' => 2, 'kelas' => 'XII', 'jurusan' => 'RPL', 'nomor_kelas' => '2'],
                (object)['id' => 3, 'kelas' => 'XI', 'jurusan' => 'TKJ', 'nomor_kelas' => '1'],
                (object)['id' => 4, 'kelas' => 'X', 'jurusan' => 'DKV', 'nomor_kelas' => '1'],
            ]);
        }

        return view('registerSiswa', compact('kelases'));
    }

    // Memproses data pendaftaran siswa
    public function registerSiswa(Request $request)
    {
        // Validasi input form
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'nis'      => 'required|string|max:255|unique:siswas,nis', 
            'kelas_id' => 'required', 
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique'      => 'Email sudah terdaftar.',
            'nis.required'      => 'NIS wajib diisi.',
            'nis.unique'        => 'NIS sudah terdaftar.',
            'kelas_id.required' => 'Pilih kelas terlebih dahulu.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
            'password.min'      => 'Password minimal 8 karakter.'
        ]);

        $siswaUser = null;

        // Gunakan DB Transaction agar jika satu gagal, keduanya dibatalkan
        DB::transaction(function () use ($request, &$siswaUser) {
            // 1. Simpan ke tabel `users`
            $siswaUser = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'siswa',
            ]);

            // 2. Simpan ke tabel `siswas`
            Siswa::create([
                'user_id'  => $siswaUser->id,
                'kelas_id' => $request->kelas_id,
                'nis'      => $request->nis,
                'nama'     => $request->name,
            ]);
        });

        // Auto login akun user yang baru dibuat
        Auth::login($siswaUser);

        return redirect()->route('student.overview')->with('success', 'Pendaftaran siswa berhasil!');
    }
}