<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Arsip extends Model
{
    protected $table = 'arsip';
    
    protected $fillable = [
        'nama_dokumen',
        'kategori_arsip',
        'deskripsi',
        'nomor_dokumen',
        'tanggal_dokumen',
        'file_dokumen',
        'akses',
        'tahun_arsip',
        'lokasi_fisik',
        'keterangan',
        'created_by'
    ];

    protected $casts = [
        'tanggal_dokumen' => 'date',
    ];

    /**
     * Relationship dengan user yang membuat arsip
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope untuk arsip berdasarkan kategori
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori_arsip', $kategori);
    }

    /**
     * Scope untuk arsip berdasarkan akses
     */
    public function scopeAkses($query, $akses)
    {
        return $query->where('akses', $akses);
    }

    /**
     * Scope untuk arsip berdasarkan tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun_arsip', $tahun);
    }

    /**
     * Accessor untuk kategori formatted
     */
    public function getKategoriFormattedAttribute()
    {
        $kategori = [
            'akademik' => 'Akademik',
            'kesiswaan' => 'Kesiswaan',
            'administrasi' => 'Administrasi',
            'keuangan' => 'Keuangan',
            'laporan' => 'Laporan',
            'sarana_prasarana' => 'Sarana & Prasarana',
            'lainnya' => 'Lainnya'
        ];

        return $kategori[$this->kategori_arsip] ?? $this->kategori_arsip;
    }

    /**
     * Accessor untuk akses formatted
     */
    public function getAksesFormattedAttribute()
    {
        $akses = [
            'publik' => 'Publik',
            'terbatas' => 'Terbatas',
            'rahasia' => 'Rahasia'
        ];

        return $akses[$this->akses] ?? $this->akses;
    }

    /**
     * Accessor untuk file extension
     */
    public function getFileExtensionAttribute()
    {
        return $this->file_dokumen ? pathinfo($this->file_dokumen, PATHINFO_EXTENSION) : null;
    }

    /**
     * Accessor untuk file size dengan error handling
     */
    public function getFileSizeAttribute()
    {
        if (!$this->file_dokumen) return null;
        
        try {
            if (Storage::disk('public')->exists($this->file_dokumen)) {
                $size = Storage::disk('public')->size($this->file_dokumen);
                
                if ($size >= 1048576) {
                    return round($size / 1048576, 2) . ' MB';
                } elseif ($size >= 1024) {
                    return round($size / 1024, 2) . ' KB';
                } else {
                    return $size . ' bytes';
                }
            }
            return 'File tidak ditemukan';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Accessor untuk file URL dengan error handling
     */
    public function getFileUrlAttribute()
    {
        if (!$this->file_dokumen) return null;
        
        try {
            if (Storage::disk('public')->exists($this->file_dokumen)) {
                return asset('storage/' . $this->file_dokumen);
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Check jika file ada dengan error handling
     */
    public function getFileExistsAttribute()
    {
        if (!$this->file_dokumen) return false;
        
        try {
            return Storage::disk('public')->exists($this->file_dokumen);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get file icon dengan fallback
     */
    public function getFileIconAttribute()
    {
        if (!$this->file_dokumen) return 'file';
        
        $extension = strtolower($this->file_extension);
        
        $icons = [
            'pdf' => 'file-pdf',
            'doc' => 'file-word',
            'docx' => 'file-word',
            'xls' => 'file-excel',
            'xlsx' => 'file-excel',
            'ppt' => 'file-powerpoint',
            'pptx' => 'file-powerpoint',
            'jpg' => 'file-image',
            'jpeg' => 'file-image',
            'png' => 'file-image',
        ];

        return $icons[$extension] ?? 'file';
    }

    /**
     * Get file color dengan fallback
     */
    public function getFileColorAttribute()
    {
        if (!$this->file_dokumen) return 'gray';
        
        $extension = strtolower($this->file_extension);
        
        $colors = [
            'pdf' => 'red',
            'doc' => 'blue',
            'docx' => 'blue',
            'xls' => 'green',
            'xlsx' => 'green',
            'ppt' => 'orange',
            'pptx' => 'orange',
            'jpg' => 'purple',
            'jpeg' => 'purple',
            'png' => 'purple',
        ];

        return $colors[$extension] ?? 'gray';
    }
}