<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Abilities\HasParentModel;

class FeedbackNotification extends Notification
{
    use HasParentModel; // keeps the table id as notification id

    public static function boot()
    {
        parent::boot();

        // All the queries are the same as User but only for admins
        static::addGlobalScope(function ($query) {
            $query->where('type', 'FEEDBACK');
        });
    }

    public function feedback_giver() {
      return $this->belongsTo(User::class, 'fb_giver');
    }

    public function content() {
      return $this->belongsTo(Content::class, 'rated_content');
    }
}
