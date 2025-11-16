<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    
    protected $fillable = [
        'isbn',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'kategori_id',
        'stok',
        'dipinjam',
        'rak_buku',
        'deskripsi',
        'cover',
        'status'
    ];

    protected $casts = [
        'tahun_terbit' => 'integer',
        'stok' => 'integer',
        'dipinjam' => 'integer'
    ];

    // ✅ SCOPE YANG DIPERBAIKI - Sesuai dengan struktur database
    public function scopeKategoriAktif($query)
    {
        return $query->whereHas('kategori', function($q) {
            $q->where('status', 1); // Kolom status bertipe tinyint(1) dengan nilai 1 untuk aktif
        });
    }

    // Relasi ke KategoriBuku
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_id');
    }

    // Relasi ke PeminjamanBuku
    public function peminjaman(): HasMany
    {
        return $this->hasMany(PeminjamanBuku::class);
    }

    // Accessor untuk stok tersedia
    public function getStokTersediaAttribute(): int
    {
        return $this->stok - $this->dipinjam;
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'tersedia' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'dipinjam' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'rusak' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'hilang' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
        };
    }

    // Accessor untuk status text
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'tersedia' => 'Tersedia',
            'dipinjam' => 'Dipinjam',
            'rusak' => 'Rusak',
            'hilang' => 'Hilang',
            default => $this->status
        };
    }

    // Scope untuk buku tersedia
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia')
                    ->whereRaw('stok > dipinjam');
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }
        
        return $query->where(function($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
              ->orWhere('penulis', 'like', "%{$search}%")
              ->orWhere('penerbit', 'like', "%{$search}%")
              ->orWhere('isbn', 'like', "%{$search}%")
              ->orWhere('rak_buku', 'like', "%{$search}%")
              ->orWhereHas('kategori', function($q2) use ($search) {
                  $q2->where('nama_kategori', 'like', "%{$search}%");
              });
        });
    }

    // Scope untuk filter kategori
    public function scopeByKategori($query, $kategoriId)
    {
        if (empty($kategoriId)) {
            return $query;
        }
        
        return $query->where('kategori_id', $kategoriId);
    }

    // Method untuk cek apakah bisa dipinjam
    public function getCanBorrowAttribute(): bool
    {
        return $this->status === 'tersedia' && $this->stok_tersedia > 0;
    }

    // Method untuk update stok ketika dipinjam
    public function pinjam(): bool
    {
        \Log::info('Method pinjam() dipanggil', [
            'buku_id' => $this->id,
            'judul' => $this->judul,
            'status_sebelum' => $this->status,
            'stok_sebelum' => $this->stok,
            'dipinjam_sebelum' => $this->dipinjam
        ]);

        try {
            \DB::beginTransaction();

            // Reload data untuk memastikan data terbaru
            $freshBuku = self::find($this->id);
            
            $stokTersedia = $freshBuku->stok - $freshBuku->dipinjam;
            
            if ($freshBuku->status === 'tersedia' && $stokTersedia > 0) {
                $freshBuku->dipinjam += 1;
                $freshBuku->save();
                
                // Update status jika stok habis
                $stokTersediaSetelah = $freshBuku->stok - $freshBuku->dipinjam;
                if ($stokTersediaSetelah === 0) {
                    $freshBuku->status = 'dipinjam';
                    $freshBuku->save();
                }
                
                \DB::commit();
                \Log::info('Peminjaman BERHASIL');
                return true;
            }
            
            \DB::rollBack();
            \Log::warning('Peminjaman GAGAL - kondisi tidak memenuhi');
            return false;
            
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error saat meminjam buku: ' . $e->getMessage());
            return false;
        }
    }

    // Method untuk mengembalikan buku
    public function kembalikan(): bool
    {
        if ($this->dipinjam > 0) {
            $this->dipinjam -= 1;
            $this->save();
            
            // Update status jika sebelumnya status dipinjam
            if ($this->status === 'dipinjam') {
                $stokTersedia = $this->stok - $this->dipinjam;
                if ($stokTersedia > 0) {
                    $this->status = 'tersedia';
                    $this->save();
                }
            }
            
            return true;
        }
        
        return false;
    }
}