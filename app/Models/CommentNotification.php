<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Abilities\HasParentModel;

class CommentNotification extends Notification
{
    use HasParentModel; // keeps the table id as notification id

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
