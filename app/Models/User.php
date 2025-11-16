<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'is_active',
        'foto_profil',
        'last_seen'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_seen' => 'datetime'
    ];

    // Relationships
    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    // System Notifications Relationship
    public function systemNotifications()
    {
        return $this->hasMany(SystemNotification::class, 'created_by');
    }

    public function unreadSystemNotifications()
    {
        return SystemNotification::active()
            ->forUserRole($this->role)
            ->unreadByUser($this->id)
            ->get();
    }

    public function markSystemNotificationsAsRead()
    {
        $unreadNotifications = $this->unreadSystemNotifications();
        foreach ($unreadNotifications as $notification) {
            $notification->markAsRead($this->id);
        }
    }

    // Role Methods
    public function isAdmin()
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    public function isGuru()
    {
        return $this->role === 'guru' || $this->hasRole('guru');
    }

    public function isSiswa()
    {
        return $this->role === 'siswa' || $this->hasRole('siswa');
    }

    public function getRoleData()
    {
        if ($this->isAdmin()) {
            return $this;
        } elseif ($this->isGuru()) {
            return $this->guru;
        } elseif ($this->isSiswa()) {
            return $this->siswa;
        }
        
        return null;
    }

    // Accessor untuk foto profil
    public function getFotoProfilUrlAttribute()
    {
        if ($this->foto_profil) {
            return asset('storage/' . $this->foto_profil);
        }
        return asset('images/default-avatar.png');
    }

    // Method untuk cek status online
    public function isOnline()
    {
        return $this->last_seen && $this->last_seen->gt(now()->subMinutes(5));
    }

    // Get user role name
    public function getRoleNameAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'guru' => 'Guru',
            'siswa' => 'Siswa',
            default => 'User'
        };
    }
}