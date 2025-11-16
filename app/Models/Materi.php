<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = [
        'mapel_id',
        'guru_id',
        'judul',
        'deskripsi',
        'file',
        'link',
    ];

    // Relasi dengan Mata Pelajaran
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    // Relasi dengan Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // Accessor untuk file URL
    public function getFileUrlAttribute()
    {
        return $this->file ? asset('storage/materi/' . $this->file) : null;
    }

    // Accessor untuk file type
    public function getFileTypeAttribute()
    {
        if (!$this->file) return null;
        
        $extension = strtolower(pathinfo($this->file, PATHINFO_EXTENSION));
        
        return match($extension) {
            'pdf' => 'pdf',
            'doc', 'docx' => 'word',
            'ppt', 'pptx' => 'powerpoint',
            'xls', 'xlsx' => 'excel',
            'jpg', 'jpeg', 'png', 'gif' => 'image',
            'mp4', 'avi', 'mov' => 'video',
            'zip', 'rar' => 'archive',
            default => 'file'
        };
    }

    // Accessor untuk icon berdasarkan file type
    public function getFileIconAttribute()
    {
        return match($this->file_type) {
            'pdf' => 'fas fa-file-pdf',
            'word' => 'fas fa-file-word',
            'powerpoint' => 'fas fa-file-powerpoint',
            'excel' => 'fas fa-file-excel',
            'image' => 'fas fa-file-image',
            'video' => 'fas fa-file-video',
            'archive' => 'fas fa-file-archive',
            default => 'fas fa-file'
        };
    }

    // Accessor untuk warna icon
    public function getFileIconColorAttribute()
    {
        return match($this->file_type) {
            'pdf' => 'text-red-500',
            'word' => 'text-blue-500',
            'powerpoint' => 'text-orange-500',
            'excel' => 'text-green-500',
            'image' => 'text-purple-500',
            'video' => 'text-pink-500',
            'archive' => 'text-yellow-500',
            default => 'text-gray-500'
        };
    }

    // Method untuk cek apakah bisa di-preview
    public function getCanPreviewAttribute()
    {
        return in_array($this->file_type, ['pdf', 'image']);
    }

    // Scope untuk materi berdasarkan guru
    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    // Scope untuk materi berdasarkan mapel
    public function scopeByMapel($query, $mapelId)
    {
        return $query->where('mapel_id', $mapelId);
    }

    // Method untuk format tanggal
    public function getTanggalDibuatAttribute()
    {
        return $this->created_at->translatedFormat('d F Y');
    }

    // Method untuk waktu relatif
    public function getWaktuRelatifAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}