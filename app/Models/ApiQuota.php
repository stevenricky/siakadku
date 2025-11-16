<?php
// app/Models/ApiQuota.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiQuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_key_id',
        'date',
        'request_count',
        'limit_exceeded'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function apiKey(): BelongsTo
    {
        return $this->belongsTo(ApiKey::class);
    }
}