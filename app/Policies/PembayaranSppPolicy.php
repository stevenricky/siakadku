<?php

namespace App\Policies;

use App\Models\PembayaranSpp;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PembayaranSppPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        // Admin dan guru bisa melihat daftar pembayaran
        return in_array($user->role, ['admin', 'guru']);
    }

    public function view(User $user, PembayaranSpp $pembayaran)
    {
        // Admin bisa melihat semua, guru hanya siswa di kelasnya
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'guru') {
            $guru = $user->guru;
            if ($guru->kelasWali) {
                return $pembayaran->siswa->kelas_id === $guru->kelasWali->id;
            }
        }

        return false;
    }

    public function verify(User $user, PembayaranSpp $pembayaran)
    {
        // Hanya guru (wali kelas) yang bisa verifikasi
        if ($user->role !== 'guru') {
            return false;
        }

        $guru = $user->guru;

        // Cek apakah guru adalah wali kelas
        if (!$guru->kelasWali) {
            return false;
        }

        // Cek apakah pembayaran masih pending
        if ($pembayaran->status_verifikasi !== PembayaranSpp::STATUS_PENDING) {
            return false;
        }

        // Cek apakah siswa berada di kelas yang diampu guru
        return $pembayaran->siswa->kelas_id === $guru->kelasWali->id;
    }

    public function downloadBukti(User $user, PembayaranSpp $pembayaran)
    {
        // Admin bisa download semua, guru hanya siswa di kelasnya
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'guru') {
            $guru = $user->guru;
            if ($guru->kelasWali) {
                return $pembayaran->siswa->kelas_id === $guru->kelasWali->id;
            }
        }

        return false;
    }

    public function exportLaporan(User $user)
    {
        // Hanya admin dan guru yang bisa export laporan
        return in_array($user->role, ['admin', 'guru']);
    }
}