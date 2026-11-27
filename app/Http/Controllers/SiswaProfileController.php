<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa()->with('kelas')->first();

        return view('Dashboard.siswa.profile', compact('siswa', 'user'));
    }
}