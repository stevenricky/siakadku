<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_key_id',
        'endpoint',
        'method',
        'response_code',
        'response_time',
        'ip_address',
        'user_agent',
        'request_params',
        'response_content',
        'requested_at'
    ];

    protected $casts = [
        'request_params' => 'array',
        'response_content' => 'array',
        'requested_at' => 'datetime'
    ];

    // OVERRIDE: Gunakan requested_at sebagai created_at
    public function getCreatedAtAttribute()
    {
        return $this->requested_at;
    }

    // OVERRIDE: Gunakan requested_at sebagai updated_at  
    public function getUpdatedAtAttribute()
    {
        return $this->requested_at;
    }

    public function apiKey(): BelongsTo
    {
        return $this->belongsTo(ApiKey::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('requested_at', today());
    }

    public function scopeFailed($query)
    {
        return $query->where('response_code', '>=', 400);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('response_code', '<', 400);
    }

    public function scopeLatestRequest($query)
    {
        return $query->orderBy('requested_at', 'desc');
    }
}