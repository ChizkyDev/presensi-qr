<?php

namespace App\Http\Controllers;

use App\Models\IzinSakit;
use Illuminate\Http\Request;

class LeaveApprovalController extends Controller
{
    public function index(Request $request)
    {
        $requests = IzinSakit::with(['siswa.kelas'])
            ->latest('tanggal')
            ->latest('id')
            ->get();

        $pendingCount = IzinSakit::where('status_approve', 'pending')->count();
        $approvedCount = IzinSakit::whereIn('status_approve', ['diterima', 'approved'])->count();
        $rejectedCount = IzinSakit::whereIn('status_approve', ['ditolak', 'rejected'])->count();

        // Sesuaikan path view ini dengan lokasi file Blade kamu (misal: Dashboard.admin.leave_approvals atau Dashboard.admin.leave-approvals)
        return view('Dashboard.admin.leave_approvals', compact(
            'requests',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status_approve' => 'required|in:pending,diterima,ditolak,approved,rejected',
        ]);

        $izin = IzinSakit::findOrFail($id);
        $izin->update([
            'status_approve' => $validated['status_approve'],
        ]);

        return redirect()->back()->with('success', 'Status persetujuan berhasil diperbarui!');
    }
}