<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruLoginController extends Controller
{
    // Menampilkan halaman form login guru
    public function showLoginForm()
    {
        return view('loginGuru');
    }

    // Memproses data login guru
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'nip' => 'required|string',
            'password' => 'required|string',
        ], [
            'nip.required' => 'Nomor Induk Pendidik (NIP) wajib diisi!',
            'password.required' => 'Password wajib diisi!',
        ]);

        // Aturan autentikasi menggunakan kolom 'nip'
        $credentials = [
            'nip' => $request->nip,
            'password' => $request->password,
        ];

        // Coba lakukan login (menggunakan guard default/user)
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            // Redirect ke halaman dashboard guru setelah berhasil login
            return redirect()->intended('/guru/dashboard');
        }

        // Jika login gagal
        return back()->withErrors([
            'nip' => 'NIP atau password yang Anda masukkan salah.',
        ])->onlyInput('nip');
    }

    // Logout Guru
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('guru.login');
    }
}