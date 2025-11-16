<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_key',
        'user_id',
        'permissions',
        'status',
        'last_used_at',
        'expires_at',
        'rate_limit',
        'allowed_ips',
        'webhook_url',
        'description'
    ];

    protected $casts = [
        'permissions' => 'array',
        'allowed_ips' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ApiLog::class);
    }

    public function quotas(): HasMany
    {
        return $this->hasMany(ApiQuota::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function canAccess(string $endpoint, string $action = 'read'): bool
    {
        if (!$this->isActive() || $this->isExpired()) {
            return false;
        }

        $permissions = $this->permissions ?? [];
        
        // Debug: log permissions
        \Log::info('API Permission Check', [
            'api_key' => $this->name,
            'endpoint' => $endpoint,
            'action' => $action,
            'permissions' => $permissions
        ]);

        // Format 1: Check for array format ['guru' => ['read' => true]]
        if (isset($permissions[$endpoint][$action])) {
            return $permissions[$endpoint][$action] === true;
        }

        // Format 2: Check for string format ['guru:read', 'guru:create']
        foreach ($permissions as $permission) {
            if (is_string($permission)) {
                $parts = explode(':', $permission);
                if (count($parts) === 2) {
                    $permEndpoint = trim($parts[0]);
                    $permAction = trim($parts[1]);
                    
                    if ($permEndpoint === $endpoint && $permAction === $action) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function getTodayRequestCount(): int
    {
        return $this->quotas()
            ->where('date', today())
            ->value('request_count') ?? 0;
    }

    /**
     * Convert permissions to display format
     */
    public function getDisplayPermissionsAttribute(): array
    {
        $permissions = $this->permissions ?? [];
        $display = [];

        foreach ($permissions as $key => $value) {
            if (is_array($value)) {
                // Format: ['guru' => ['read' => true, 'create' => true]]
                foreach ($value as $action => $allowed) {
                    if ($allowed) {
                        $display[] = "{$key}:{$action}";
                    }
                }
            } elseif (is_string($value)) {
                // Format: ['guru:read', 'guru:create']
                $display[] = $value;
            }
        }

        return $display;
    }
}