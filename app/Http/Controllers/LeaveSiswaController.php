<?php

namespace App\Http\Controllers;

use App\Models\IzinSakit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LeaveSiswaController extends Controller
{
    // Jatah izin per tahun (hari). Pindahkan ke tabel settings kalau ingin bisa diubah admin.
    private const ANNUAL_QUOTA = 12;

    // Pengajuan paling lama (hari) dalam satu kali kirim.
    private const MAX_DAYS_PER_REQUEST = 30;

    public function index(Request $request)
    {
        $user  = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $quota     = self::ANNUAL_QUOTA;
        $daysUsed  = $this->daysUsed($siswa->id);
        $remaining = max(0, $quota - $daysUsed);

        $showAll = $request->boolean('all');
        $query   = IzinSakit::where('siswa_id', $siswa->id)->latest('id');
        $history = $showAll ? $query->paginate(10)->withQueryString() : $query->take(3)->get();

        return view('Dashboard.siswa.leave', compact('quota', 'daysUsed', 'remaining', 'history', 'showAll'));
    }

    public function store(Request $request)
    {
        $siswa = Auth::user()->siswa;
        abort_unless($siswa, 403);

        $data = $request->validate([
            'jenis'           => ['required', 'in:sakit,izin,darurat'],
            'tanggal'         => ['required', 'date', 'after_or_equal:' . now()->subDays(7)->toDateString()],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal'],
            'keterangan'      => ['required', 'string', 'min:10', 'max:500'],
            'bukti_file'      => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'kontak_darurat'  => ['required', 'regex:/^[0-9+\-\s()]{8,20}$/'],
            'hubungan_kontak' => ['required', 'in:Father,Mother,Guardian,Sibling,Relative,Other'],
        ], [
            'tanggal.after_or_equal'         => 'Tanggal mulai maksimal 7 hari ke belakang.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'keterangan.min'                 => 'Alasan minimal 10 karakter.',
            'bukti_file.required'            => 'Dokumen pendukung wajib diunggah.',
            'bukti_file.mimes'               => 'Dokumen harus berformat PDF, JPG, atau PNG.',
            'bukti_file.max'                 => 'Ukuran dokumen maksimal 5 MB.',
            'kontak_darurat.regex'           => 'Nomor kontak tidak valid.',
        ]);

        $start = Carbon::parse($data['tanggal'])->startOfDay();
        $end   = Carbon::parse($data['tanggal_selesai'])->startOfDay();
        $days  = (int) $start->diffInDays($end) + 1;

        if ($days > self::MAX_DAYS_PER_REQUEST) {
            throw ValidationException::withMessages([
                'tanggal_selesai' => 'Satu pengajuan maksimal ' . self::MAX_DAYS_PER_REQUEST . ' hari.',
            ]);
        }

        // Tidak boleh bertabrakan dengan pengajuan lain yang masih pending atau sudah disetujui
        $overlap = IzinSakit::where('siswa_id', $siswa->id)
            ->whereIn('status_approve', ['pending', 'diterima'])
            ->where('tanggal', '<=', $end->toDateString())
            ->whereRaw('COALESCE(tanggal_selesai, tanggal) >= ?', [$start->toDateString()])
            ->exists();

        if ($overlap) {
            throw ValidationException::withMessages([
                'tanggal' => 'Anda sudah punya pengajuan pada rentang tanggal tersebut.',
            ]);
        }

        // Sisa jatah (hapus blok ini kalau jatah tidak ingin dibatasi)
        $remaining = max(0, self::ANNUAL_QUOTA - $this->daysUsed($siswa->id));
        if ($days > $remaining) {
            throw ValidationException::withMessages([
                'tanggal_selesai' => "Pengajuan {$days} hari melebihi sisa jatah ({$remaining} hari).",
            ]);
        }

        // Disimpan di disk privat (storage/app), tidak bisa diakses lewat URL publik
        $path = $request->file('bukti_file')->store('izin-sakit');

        IzinSakit::create([
            'siswa_id'        => $siswa->id,
            'tanggal'         => $start->toDateString(),
            'tanggal_selesai' => $end->toDateString(),
            'jumlah_hari'     => $days,
            'jenis'           => $data['jenis'],
            'keterangan'      => $data['keterangan'],
            'bukti_file'      => $path,
            'kontak_darurat'  => $data['kontak_darurat'],
            'hubungan_kontak' => $data['hubungan_kontak'],
            'status_approve'  => 'pending',
        ]);

        $message = $days > 3
            ? 'Pengajuan terkirim. Karena lebih dari 3 hari, pengajuan memerlukan verifikasi tambahan dari admin.'
            : 'Pengajuan izin berhasil dikirim dan menunggu persetujuan.';

        return redirect()->route('siswa.leave')->with('success', $message);
    }

    /** Unduh dokumen pendukung: hanya pemilik pengajuan atau admin. */
    public function document($id)
    {
        $leave = IzinSakit::with('siswa')->findOrFail($id);
        $user  = Auth::user();

        $isOwner = $leave->siswa && $leave->siswa->user_id === $user->id;
        abort_unless($isOwner || $user->role === 'admin', 403);
        abort_unless($leave->bukti_file && Storage::exists($leave->bukti_file), 404);

        return Storage::response($leave->bukti_file);
    }

    /** Jumlah hari izin yang sudah disetujui tahun ini. */
    private function daysUsed(int $siswaId): int
    {
        return (int) IzinSakit::where('siswa_id', $siswaId)
            ->where('status_approve', 'diterima')
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah_hari');
    }
}