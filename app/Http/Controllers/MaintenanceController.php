<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SettingsHelper;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function access(Request $request)
    {
        $request->validate([
            'access_code' => 'required|string'
        ]);
        
        // Kode akses tetap 261102
        if ($request->access_code === '261102') {
            // Set session untuk allow access selama maintenance
            session(['maintenance_access_granted' => true]);
            
            // Cek apakah user sudah login
            if (Auth::check()) {
                // Jika sudah login, redirect ke dashboard sesuai role
                $user = Auth::user();
                return match($user->role) {
                    'admin' => redirect('/admin/dashboard'),
                    'guru' => redirect('/guru/dashboard'),
                    'siswa' => redirect('/siswa/dashboard'),
                    default => redirect('/admin/dashboard')
                };
            } else {
                // Jika belum login, redirect ke admin dashboard
                // Atau ke halaman login biasa
                return redirect('/admin/dashboard');
            }
        }
        
        return back()->withErrors(['access_code' => 'Kode akses maintenance tidak valid.']);
    }
}