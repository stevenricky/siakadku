<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumLike extends Model
{
    use HasFactory;

    protected $table = 'forum_likes';

    protected $fillable = [
        'forum_id',
        'user_id'
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