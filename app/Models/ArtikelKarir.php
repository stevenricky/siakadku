<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class ArtikelKarir extends Model
{
    protected $table = 'artikel_karir';
    
    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'kategori',
        'gambar',
        'penulis',
        'sumber',
        'tags',
        'status',
        'views'
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    /**
     * Scope untuk artikel publik
     */
    public function scopePublik($query)
    {
        return $query->where('status', 'publik');
    }

    /**
     * Scope untuk artikel trending
     */
    public function scopeTrending($query)
    {
        return $query->where('views', '>', 100)
                    ->orderBy('views', 'desc');
    }

    /**
     * Accessor untuk konten singkat
     */
    protected function kontenSingkat(): Attribute
    {
        return Attribute::make(
            get: fn () => str($this->konten)->limit(150),
        );
    }

    /**
     * Accessor untuk tanggal format
     */
    public function getTanggalFormatAttribute()
    {
        return $this->created_at->translatedFormat('d F Y');
    }

    /**
     * Accessor untuk waktu baca
     */
    public function getWaktuBacaAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->konten));
        $minutes = ceil($wordCount / 200); // 200 kata per menit
        return $minutes . ' menit baca';
    }
}