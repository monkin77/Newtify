<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Abilities\HasParentModel;

class MessageNotification extends Notification
{
    use HasParentModel; // keeps the table id as notification id

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
