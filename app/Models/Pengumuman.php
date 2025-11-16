<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasNotifications;

class Pengumuman extends Model
{
    use HasNotifications;

    use HasFactory;

    protected $table = 'pengumumen';
    
    protected $fillable = [
        'judul',
        'isi',
        'target',
        'user_id',
        'kelas_id',
        'is_urgent',
        'tanggal_berlaku'
    ];

    protected $casts = [
        'is_urgent' => 'boolean',
        'tanggal_berlaku' => 'datetime'
    ];

    protected $appends = [
        'status',
        'status_badge',
        'target_formatted',
        'tanggal_format',
        'is_expired'
    ];

    // Relasi dengan User (pembuat pengumuman)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Scope untuk pengumuman aktif
    public function scopeAktif($query)
    {
        return $query->where(function($q) {
            $q->whereNull('tanggal_berlaku')
              ->orWhere('tanggal_berlaku', '>=', now());
        });
    }

    // Scope berdasarkan target
    public function scopeUntuk($query, $role)
    {
        return $query->where('target', $role)->orWhere('target', 'semua');
    }

    // Scope pengumuman mendesak
    public function scopeMendesak($query)
    {
        return $query->where('is_urgent', true);
    }

    // Accessor untuk status
    public function getStatusAttribute()
    {
        if ($this->is_expired) {
            return 'kadaluarsa';
        }
        return $this->is_urgent ? 'mendesak' : 'aktif';
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'mendesak' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'aktif' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'kadaluarsa' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
        };
    }

    // Accessor untuk target formatted
    public function getTargetFormattedAttribute()
    {
        return match($this->target) {
            'semua' => 'Semua',
            'siswa' => 'Siswa',
            'guru' => 'Guru',
            'admin' => 'Administrator',
            default => $this->target
        };
    }

    // Accessor untuk tanggal format
    public function getTanggalFormatAttribute()
    {
        return $this->created_at->translatedFormat('d F Y H:i');
    }

    // Accessor untuk tanggal berlaku format
    public function getTanggalBerlakuFormatAttribute()
    {
        if (!$this->tanggal_berlaku) {
            return 'Tidak ada batas waktu';
        }
        return $this->tanggal_berlaku->translatedFormat('d F Y H:i');
    }

    // Accessor untuk cek expired
    public function getIsExpiredAttribute()
    {
        return $this->tanggal_berlaku && $this->tanggal_berlaku < now();
    }

    // Accessor untuk excerpt
    public function getExcerptAttribute()
    {
        return \Str::limit(strip_tags($this->isi), 150);
    }

    // Method untuk cek apakah pengumuman untuk siswa
    public function getUntukSiswaAttribute()
    {
        return in_array($this->target, ['semua', 'siswa']);
    }
}