<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class LowonganKerja extends Model
{
    protected $table = 'lowongan_kerja';
    
    protected $fillable = [
        'nama_perusahaan',
        'posisi',
        'deskripsi_pekerjaan',
        'kualifikasi',
        'lokasi',
        'gaji',
        'tanggal_buka',
        'tanggal_tutup',
        'kontak_person',
        'email',
        'no_telepon',
        'website',
        'logo_perusahaan',
        'status'
    ];

    protected $casts = [
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
        'gaji' => 'decimal:2'
    ];

    /**
     * Scope untuk lowongan yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'buka')
                    ->where('tanggal_buka', '<=', now())
                    ->where('tanggal_tutup', '>=', now());
    }

    /**
     * Accessor untuk format gaji
     */
    protected function gajiFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->gaji ? 'Rp ' . number_format($this->gaji, 0, ',', '.') : 'Negosiasi',
        );
    }

    /**
     * Cek apakah lowongan masih aktif
     */
    public function getIsActiveAttribute()
    {
        return $this->status == 'buka' && 
               $this->tanggal_buka <= now() && 
               $this->tanggal_tutup >= now();
    }

    /**
     * Cek hari tersisa sampai tutup - PERBAIKAN SIMPLE
     */
    public function getHariTersisaAttribute()
    {
        if (!$this->is_active) {
            return 0;
        }
        
        // Cara sederhana: hitung selisih hari dengan ceil() untuk pembulatan ke atas
        $hariTersisa = ceil(now()->diffInDays($this->tanggal_tutup, false));
        
        return max(0, (int)$hariTersisa);
    }
}