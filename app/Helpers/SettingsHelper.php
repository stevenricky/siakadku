<?php
// app/Helpers/SettingsHelper.php
namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsHelper
{

public static function get($key, $default = null)
    {
        return Setting::where('key', $key)->value('value') ?? $default;
    }
    
    public static function maintenanceMode()
    {
        return self::get('maintenance_mode') == '1';
    }
    
    public static function maintenanceAccessCode()
    {
        // Kode akses tetap 261102
        return '261102';
    }
    
    public static function maintenanceMessage()
    {
        return self::get('maintenance_message', 'Sistem sedang dalam pemeliharaan. Silakan coba lagi nanti.');
    }


    // app/Helpers/SettingsHelper.php

public static function maintenanceStart()
{
    return self::get('maintenance_start', now()->format('Y-m-d H:i:s'));
}

public static function maintenanceEnd()
{
    // Default: 2 jam dari sekarang
    return self::get('maintenance_end', now()->addHours(2)->format('Y-m-d H:i:s'));
}

public static function maintenanceDuration()
{
    $start = self::maintenanceStart();
    $end = self::maintenanceEnd();
    
    $startTime = \Carbon\Carbon::parse($start);
    $endTime = \Carbon\Carbon::parse($end);
    
    return $startTime->diff($endTime)->format('%h jam %i menit');
}

    
   

    public static function schoolName()
    {
        return self::get('nama_sekolah', 'SMA Negeri 1 Example');
    }

    public static function schoolLogo()
    {
        $logo = self::get('logo_sekolah');
        if ($logo && Storage::disk('public')->exists($logo)) {
            return Storage::disk('public')->url($logo) . '?v=' . time();
        }
        return null;
    }

    public static function schoolAddress()
    {
        return self::get('alamat_sekolah');
    }

    public static function schoolPhone()
    {
        return self::get('telepon_sekolah');
    }

    public static function schoolEmail()
    {
        return self::get('email_sekolah');
    }

    public static function schoolWebsite()
    {
        return self::get('website_sekolah');
    }

    public static function headmaster()
    {
        return self::get('kepala_sekolah');
    }

    public static function headmasterNip()
    {
        return self::get('nip_kepala_sekolah');
    }

    public static function academicYear()
    {
        return self::get('tahun_ajaran_aktif', date('Y') . '/' . (date('Y') + 1));
    }

    public static function timezone()
    {
        return self::get('zona_waktu', 'Asia/Jakarta');
    }

    public static function dateFormat()
    {
        return self::get('format_tanggal', 'd/m/Y');
    }

    public static function appTitle()
    {
        return self::get('nama_sekolah', 'SIAKAD SMA') . ' - Sistem Informasi Akademik';
    }
}