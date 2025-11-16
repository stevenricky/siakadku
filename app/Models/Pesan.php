<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasFactory;

    protected $table = 'pesan';

    protected $fillable = [
        'pengirim_id',
        'penerima_id',
        'subjek',
        'pesan',
        'dibaca',
        'dibaca_pada'
    ];

    protected $casts = [
        'dibaca' => 'boolean',
        'dibaca_pada' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'waktu_format',
        'is_dari_saya',
        'status_badge'
    ];

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('dibaca', false);
    }

    public function scopeRead($query)
    {
        return $query->where('dibaca', true);
    }

    public function scopeUntukSaya($query)
    {
        return $query->where('penerima_id', auth()->id());
    }

    public function scopeDariSaya($query)
    {
        return $query->where('pengirim_id', auth()->id());
    }

    // Accessor untuk waktu format
    public function getWaktuFormatAttribute()
    {
        if ($this->created_at->isToday()) {
            return $this->created_at->format('H:i');
        } elseif ($this->created_at->isYesterday()) {
            return 'Kemarin ' . $this->created_at->format('H:i');
        } else {
            return $this->created_at->translatedFormat('d M Y H:i');
        }
    }

    // Accessor untuk cek apakah pesan dari user yang login
    public function getIsDariSayaAttribute()
    {
        return $this->pengirim_id === auth()->id();
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        if ($this->dibaca) {
            return 'text-green-600 dark:text-green-400';
        }
        return 'text-blue-600 dark:text-blue-400 font-semibold';
    }

    // Accessor untuk status text
    public function getStatusTextAttribute()
    {
        if ($this->dibaca) {
            return 'Dibaca ' . ($this->dibaca_pada ? $this->dibaca_pada->translatedFormat('d M Y H:i') : '');
        }
        return 'Belum dibaca';
    }

    // Helper method untuk kirim pesan
    public static function kirimPesan($pengirimId, $penerimaIds, $subjek, $pesan)
    {
        if (!is_array($penerimaIds)) {
            $penerimaIds = [$penerimaIds];
        }

        $pesanTerkirim = [];
        foreach ($penerimaIds as $penerimaId) {
            $pesanTerkirim[] = static::create([
                'pengirim_id' => $pengirimId,
                'penerima_id' => $penerimaId,
                'subjek' => $subjek,
                'pesan' => $pesan,
                'dibaca' => false,
            ]);
        }

        return $pesanTerkirim;
    }

    // Method untuk menandai sebagai dibaca
    public function tandaiDibaca()
    {
        if (!$this->dibaca) {
            $this->update([
                'dibaca' => true,
                'dibaca_pada' => now()
            ]);
        }
    }
}