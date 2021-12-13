<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageNotification extends Notification
{

    public static function boot()
    {
        parent::boot();

        // All the queries are the same as User but only for admins
        static::addGlobalScope(function ($query) {
            $query->where('type', 'MESSAGE');
        });
    }

    public function message() {
      return $this->belongsTo(Message::class, 'msg');
    }
}
