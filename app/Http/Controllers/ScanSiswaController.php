<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\SesiPresensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanSiswaController extends Controller
{
    // Lewat dari menit ke-N sejak sesi dibuat, siswa dianggap telat.
    // Sesuaikan aturannya, atau ubah jadi selalu 'hadir'.
    private const LATE_AFTER_MINUTES = 10;

    /**
     * Menampilkan halaman scan QR untuk siswa.
     */
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa()->with('kelas')->first();

        // Mengarahkan ke view resources/views/Dashboard/siswa/scan.blade.php
        return view('Dashboard.siswa.scan', compact('siswa', 'user'));
    }

    /**
     * Menyimpan absensi dari hasil scan QR.
     */
    public function store(Request $request)
    {
        $request->validate([
            'payload' => ['required', 'string', 'max:2000'],
        ]);

        $siswa = $request->user()->siswa;
        if (!$siswa) {
            return $this->fail('Akun ini bukan akun siswa.', 403);
        }

        // QR berisi JSON {"code": "<qr_token>", ...}; kalau bukan JSON, anggap isinya token langsung
        $data  = json_decode($request->payload, true);
        $token = is_array($data) ? ($data['code'] ?? null) : trim($request->payload);

        if (!$token) {
            return $this->fail('QR Code tidak valid.', 422);
        }

        $sesi = SesiPresensi::where('qr_token', $token)->first();
        if (!$sesi) {
            return $this->fail('Sesi presensi tidak ditemukan. Pastikan QR dari guru.', 404);
        }

        if ($sesi->status !== 'aktif' || $sesi->expired_at->isPast()) {
            return $this->fail('Sesi presensi sudah berakhir.', 422);
        }

        if ((int) $sesi->kelas_id !== (int) $siswa->kelas_id) {
            return $this->fail('Sesi ini bukan untuk kelas Anda.', 403);
        }

        $status = now()->greaterThan($sesi->created_at->copy()->addMinutes(self::LATE_AFTER_MINUTES))
            ? 'telat'
            : 'hadir';

        $presensi = Presensi::firstOrCreate(
            [
                'sesi_presensi_id' => $sesi->id,
                'siswa_id'         => $siswa->id,
            ],
            [
                'tanggal'  => now()->toDateString(),
                'jam_scan' => now()->format('H:i:s'),
                'status'   => $status,
            ]
        );

        if (!$presensi->wasRecentlyCreated) {
            return $this->fail('Anda sudah melakukan absensi pada sesi ini.', 409);
        }

        return response()->json([
            'success' => true,
            'status'  => $status,
            'message' => $status === 'telat'
                ? 'Absensi tercatat: Telat.'
                : 'Absensi tercatat: Hadir.',
        ]);
    }

    private function fail(string $message, int $code)
    {
        return response()->json(['success' => false, 'message' => $message], $code);
    }
}