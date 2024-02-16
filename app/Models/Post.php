<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    protected $fillable = ['title', 'body', 'author_name'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }

    public function is_liked_by_auth_user()
    {
        $user_id = Auth::id();

        foreach ($this->likes as $like) {
            if ($like->user_id == $user_id) {
                return true;
            }
        }

        return false;
    }

    public function like_count() {
        return count($this->likes);
    }
}



