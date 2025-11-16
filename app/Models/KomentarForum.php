<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomentarForum extends Model
{
    use HasFactory;

    protected $table = 'komentar_forum';
    
    protected $fillable = [
        'forum_id',
        'user_id',
        'komentar',
    ];

    // Relasi dengan Forum
    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}