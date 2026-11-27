<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function checkAccount(Request $request)
    {
        $loginInput = trim($request->input('login') ?? $request->input('email') ?? '');

        if (empty($loginInput)) {
            return response()->json([
                'exists'  => false,
                'message' => 'Silakan masukkan Email, NIS, atau NIP terlebih dahulu.'
            ], 422);
        }

        $user = User::where('email', $loginInput)
            ->orWhereHas('siswa', function ($query) use ($loginInput) {
                $query->where('nis', $loginInput);
            })
            ->orWhereHas('guru', function ($query) use ($loginInput) {
                $query->where('nip', $loginInput);
            })
            ->first();

        if ($user) {
            return response()->json([
                'exists' => true,
                'role'   => $user->role ?? null
            ]);
        }

        return response()->json([
            'exists'  => false,
            'message' => 'Akun belum terdaftar. Silakan periksa kembali Email/NIS/NIP Anda!'
        ]);
    }

    public function login(Request $request)
    {
        // 1. Validasi Input (Mendukung field name="login")
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
            'role'     => ['nullable', 'string'],
        ]);

        $loginInput   = trim($request->input('login'));
        $password     = $request->input('password');
        $selectedRole = $request->input('role');
        $remember     = $request->boolean('remember');

        // 2. Cari User Berdasarkan Email / NIS (Siswa) / NIP (Guru)
        $user = User::where('email', $loginInput)
            ->orWhereHas('siswa', function ($query) use ($loginInput) {
                $query->where('nis', $loginInput);
            })
            ->orWhereHas('guru', function ($query) use ($loginInput) {
                $query->where('nip', $loginInput);
            })
            ->first();

        // 3. Jika user tidak ditemukan
        if (!$user) {
            return back()->withErrors([
                'login' => 'Email/NIS/NIP tidak ditemukan dalam sistem.',
            ])->onlyInput('login');
        }

        // 4. Jika role dikirim dari modal dan tidak cocok dengan role akun di DB
        if ($selectedRole && $user->role !== $selectedRole) {
            return back()->withErrors([
                'login' => 'Akun ini terdaftar sebagai ' . ucfirst($user->role) . ', bukan ' . ucfirst($selectedRole) . '.',
            ])->onlyInput('login');
        }

        // 5. Coba Autentikasi Menggunakan Email & Password User
        if (Auth::attempt(['email' => $user->email, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            // Redirect Otomatis Sesuai Role
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'guru'  => redirect()->route('guru.dashboard'),
                'siswa' => redirect()->route('siswa.dashboard'),
                default => redirect()->route('home'),
            };
        }

        // 6. Jika Password Salah
        return back()->withErrors([
            'login' => 'Password yang Anda masukkan salah.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}