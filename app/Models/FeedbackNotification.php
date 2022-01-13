<?php

namespace App\Models;

use App\Events\ArticleLike;
use App\Events\CommentLike;

class FeedbackNotification extends Notification
{

    public static function boot()
    {
        parent::boot();

        // All the queries are the same as User but only for admins
        static::addGlobalScope(function ($query) {
            $query->where('type', 'FEEDBACK');
        });
    }

    public static function notify($user, $content, $isArticle)
    {
        self::create([
            'type' => 'FEEDBACK',
            'receiver_id' => $content->author_id,
            'fb_giver' => $user->id,
            'rated_content' => $content->id,
        ]);

        if ($isArticle)
        {
            event(new ArticleLike(
                $content->author_id, $user->name, $user->avatar, $content->id, $content->title
            ));
        }
        else
        {
            event(new CommentLike(
                $content->author_id, $user->name, $user->avatar, $content->id, $content->body
            ));
        }
    }

    public function feedback_giver() {
        return $this->belongsTo(User::class, 'fb_giver');
    }

    public function content() {
        return $this->belongsTo(Content::class, 'rated_content');
    }
}
