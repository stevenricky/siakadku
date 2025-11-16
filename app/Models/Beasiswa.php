<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Beasiswa extends Model
{
    protected $table = 'beasiswa';
    
    protected $fillable = [
        'nama_beasiswa',
        'penyelenggara',
        'deskripsi',
        'persyaratan',
        'nilai_beasiswa',
        'tanggal_buka',
        'tanggal_tutup',
        'kontak',
        'website',
        'dokumen',
        'status'
    ];

    protected $casts = [
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
        'nilai_beasiswa' => 'decimal:2'
    ];

    /**
     * Scope untuk beasiswa aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'buka')
                    ->where('tanggal_buka', '<=', now())
                    ->where('tanggal_tutup', '>=', now());
    }

    /**
     * Scope untuk beasiswa berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Accessor untuk format nilai beasiswa
     */
    public function getNilaiBeasiswaFormattedAttribute()
    {
        return $this->nilai_beasiswa ? 'Rp ' . number_format($this->nilai_beasiswa, 0, ',', '.') : 'Tidak ditentukan';
    }

    /**
     * Accessor untuk status berdasarkan tanggal
     */
    public function getStatusOtomatisAttribute()
    {
        $today = now();
        if ($today->between($this->tanggal_buka, $this->tanggal_tutup)) {
            return 'buka';
        }
        return 'tutup';
    }

    /**
     * Cek apakah beasiswa masih aktif
     */
    public function getIsActiveAttribute()
    {
        return $this->status == 'buka' && 
               $this->tanggal_buka <= now() && 
               $this->tanggal_tutup >= now();
    }

    /**
     * Cek hari tersisa sampai tutup - PERBAIKAN SAMA DENGAN LOWONGAN_KERJA
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

    /**
     * Accessor untuk deskripsi singkat
     */
    protected function deskripsiSingkat(): Attribute
    {
        return Attribute::make(
            get: fn () => str($this->deskripsi)->limit(100),
        );
    }
}