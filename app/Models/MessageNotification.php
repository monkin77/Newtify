<?php

namespace App\Models;

use App\Events\Message;

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

    public static function notify($receiver_id, $sender, $msg)
    {
        self::create([
            'type' => 'MESSAGE',
            'receiver_id' => $receiver_id,
            'msg' => $msg->id,
        ]);

        event(new Message(
            $receiver_id, $sender->name, $sender->avatar, $sender->id, $msg->body
        ));
    }

    public function message() {
      return $this->belongsTo(Message::class, 'msg');
    }
}
