<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruRegisterController extends Controller
{
    public function create()
    {
        return view('registerGuru');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nip'      => ['required', 'string', 'max:255', 'unique:gurus,nip'],
            'subject'  => ['required', 'string', 'max:100'],   // baru
            'homeroom' => ['nullable', 'string', 'max:50'],    // baru
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->fullname,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'guru',
            ]);

            Guru::create([
                'user_id'  => $user->id,
                'nip'      => $request->nip,
                'nama'     => $request->fullname,
                'subject'  => $request->subject,    // baru
                'homeroom' => $request->homeroom,   // baru
            ]);
        });

        // Redirect ke halaman login (tanpa Auth::login)
        return redirect()->route('login')->with('success', 'Registrasi guru berhasil! Silakan login dengan akun Anda.');
    }
}