<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SystemSettingController extends Controller
{
    // Menampilkan halaman pengaturan tunggal berdasarkan tab
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'general'); // Default ke tab 'general'
        $settings = Setting::all()->pluck('value', 'key');
        
        return view('Dashboard.admin.settings', compact('settings', 'tab'));
    }

    // Menyimpan data berdasarkan tab yang sedang aktif
    public function update(Request $request)
    {
        $tab = $request->get('tab', 'general');

        if ($tab === 'security') {
            $keys = [
                'security_location_verification',
                'security_prevent_duplicate_scans',
                'security_session_auto_expiry',
                'security_admin_2fa',
            ];

            foreach ($keys as $key) {
                $value = $request->has($key) ? '1' : '0';
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }

            return redirect()->route('admin.settings.index', ['tab' => 'security'])
                             ->with('success', 'Security configuration updated successfully.');
        } 
        
        // Default / General Tab Update
        else {
            $validated = $request->validate([
                'school_name'       => 'required|string|max:255',
                'school_npsn'       => 'required|string|max:50',
                'school_timezone'   => 'required|string',
                'academic_year'     => 'required|string',
                'academic_semester' => 'required|string',
                'qr_duration'       => 'required|integer|min:1',
                'late_threshold'    => 'required|integer|min:1',
            ]);

            foreach ($validated as $key => $value) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }

            return redirect()->route('admin.settings.index', ['tab' => 'general'])
                             ->with('success', 'General configuration updated successfully.');
        }
    }
}