<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajarans';
    
    protected $fillable = [
        'tahun_awal',
        'tahun_akhir', 
        'semester',
        'tanggal_awal',
        'tanggal_akhir',
        'status'
    ];

    protected $casts = [
        'tanggal_awal' => 'date',
        'tanggal_akhir' => 'date',
        'status' => 'string'
    ];

    // ⭐⭐ TAMBAHKAN ACCESSOR UNTUK KOMPATIBILITAS ⭐⭐
    public function getTahunAttribute()
    {
        return $this->tahun_awal; // atau return "{$this->tahun_awal}/{$this->tahun_akhir}";
    }

    // Accessor untuk format tahun ajaran
    public function getTahunAjaranAttribute()
    {
        return "{$this->tahun_awal}/{$this->tahun_akhir}";
    }

    // Accessor untuk tanggal_mulai (kompatibilitas)
    public function getTanggalMulaiAttribute()
    {
        return $this->tanggal_awal;
    }

    // Accessor untuk tanggal_selesai (kompatibilitas)
    public function getTanggalSelesaiAttribute()
    {
        return $this->tanggal_akhir;
    }

    // Mutator untuk status (konversi ke string)
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value ? 'aktif' : 'tidak aktif';
    }

    // Accessor untuk status (konversi ke boolean)
    public function getStatusAttribute($value)
    {
        return $value === 'aktif';
    }

    // Relasi dengan Siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'tahun_ajaran_id');
    }

    /**
     * Relasi dengan SPP
     */
    public function spp(): HasMany
    {
        return $this->hasMany(Spp::class, 'tahun_ajaran_id');
    }

    // Relasi dengan Semester
    public function semesters()
    {
        return $this->hasMany(Semester::class, 'tahun_ajaran_id');
    }

    // Relasi dengan Nilai
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'tahun_ajaran_id');
    }

    // Scope untuk tahun ajaran aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Accessor untuk nama lengkap
    public function getNamaLengkapAttribute()
    {
        return "Tahun Ajaran {$this->tahun_awal}/{$this->tahun_akhir} - Semester {$this->semester}";
    }

    /**
     * Method untuk mendapatkan total penerimaan SPP
     */
    public function getTotalPenerimaanSppAttribute()
    {
        return $this->spp()
            ->where('status', 'lunas')
            ->sum('nominal');
    }
}