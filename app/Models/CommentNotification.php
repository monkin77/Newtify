<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentNotification extends Notification
{
    public static function boot()
    {
        parent::boot();

        // All the queries are the same as User but only for admins
        static::addGlobalScope(function ($query) {
            $query->where('type', 'COMMENT');
        });
    }

    public function comment() {
      return $this->belongsTo(Comment::class, 'new_comment');
    }
}
