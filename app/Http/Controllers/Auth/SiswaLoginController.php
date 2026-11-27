<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaLoginController extends Controller
{
    // Menampilkan halaman login siswa
    public function showLoginForm()
    {
        return view('loginSiswa');
    }

    // Memproses data login siswa berbasis NIS
    public function login(Request $request)
    {
        $request->validate([
            'nis' => 'required|string',
            'password' => 'required|string',
        ], [
            'nis.required' => 'Nomor Induk Siswa (NIS) wajib diisi!',
            'password.required' => 'Password wajib diisi!',
        ]);

        $credentials = [
            'nis' => $request->nis,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/siswa/dashboard');
        }

        return back()->withErrors([
            'nis' => 'NIS atau password yang Anda masukkan salah.',
        ])->onlyInput('nis');
    }

    // Logout Siswa
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('siswa.login');
    }
}