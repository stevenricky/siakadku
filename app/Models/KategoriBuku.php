<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriBuku extends Model
{
    use HasFactory;

    protected $table = 'kategori_buku';
    
    protected $fillable = [
        'nama_kategori',
        'kode_kategori',
        'deskripsi',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // PERBAIKAN: Sesuaikan dengan foreign key di model Buku
    public function buku(): HasMany
    {
        return $this->hasMany(Buku::class, 'kategori_id');
    }

    // Accessor untuk jumlah buku
    public function getJumlahBukuAttribute(): int
    {
        return $this->buku()->count();
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute(): string
    {
        return $this->status 
            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
    }

    // Accessor untuk status text
    public function getStatusTextAttribute(): string
    {
        return $this->status ? 'Aktif' : 'Nonaktif';
    }

    // Scope aktif
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_kategori', 'like', "%{$search}%")
                    ->orWhere('kode_kategori', 'like', "%{$search}%");
    }
}