<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'forum';

    protected $fillable = [
        'user_id',
        'judul',
        'isi',
        'kategori',
        'like_count',
        'view_count',
        'is_pinned',
        'is_locked'
    ];

    protected $casts = [
        'like_count' => 'integer',
        'view_count' => 'integer',
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
    ];

    protected $appends = [
        'kategori_formatted',
        'waktu_format',
        'excerpt',
        'is_liked_by_user' // Tambahkan ini
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Komentar
    public function komentar()
    {
        return $this->hasMany(KomentarForum::class);
    }

    // Relasi dengan Likes
    public function likes()
    {
        return $this->hasMany(ForumLike::class);
    }

    // Scope berdasarkan kategori
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // Scope untuk diskusi aktif (tidak terkunci)
    public function scopeAktif($query)
    {
        return $query->where('is_locked', false);
    }

    // Scope untuk diskusi pinned
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    // Accessor untuk kategori formatted
    public function getKategoriFormattedAttribute()
    {
        return match($this->kategori) {
            'pelajaran' => 'Pelajaran',
            'umum' => 'Umum',
            'ekstrakurikuler' => 'Ekstrakurikuler',
            default => $this->kategori
        };
    }

    // Accessor untuk waktu format
    public function getWaktuFormatAttribute()
    {
        return $this->created_at->translatedFormat('d F Y H:i');
    }

    // Accessor untuk excerpt
    public function getExcerptAttribute()
    {
        return \Str::limit(strip_tags($this->isi), 150);
    }

    // Accessor untuk cek apakah user sudah like
    public function getIsLikedByUserAttribute()
    {
        if (!auth()->check()) {
            return false;
        }
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    // Method untuk tambah view
    public function tambahView()
    {
        $this->increment('view_count');
    }

    // Method untuk toggle like
    public function toggleLike()
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        // Cek apakah user sudah like
        $existingLike = $this->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            // Jika sudah like, hapus like (unlike)
            $existingLike->delete();
            $this->decrement('like_count');
            return 'unliked';
        } else {
            // Jika belum like, tambah like
            $this->likes()->create(['user_id' => $user->id]);
            $this->increment('like_count');
            return 'liked';
        }
    }

    // Method untuk cek apakah user sudah like
    public function isLikedByUser($userId = null)
    {
        if (!$userId) {
            $userId = auth()->id();
        }
        return $this->likes()->where('user_id', $userId)->exists();
    }
}