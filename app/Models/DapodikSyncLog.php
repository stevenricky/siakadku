<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DapodikSyncLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'sync_type',
        'status',
        'data_count',
        'message',
        'details',
        'sync_date'
    ];

    protected $casts = [
        'details' => 'array',
        'sync_date' => 'datetime',
        'data_count' => 'integer'
    ];

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('sync_date', 'desc');
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'error');
    }

    // Scope untuk mengambil data terbaru dengan limit
    public function scopeLatest($query, $limit = 5)
    {
        return $query->orderBy('sync_date', 'desc')->limit($limit);
    }
}