<?php

namespace App\Models;

use App\Events\Comment;
use App\Events\CommentReply;

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

    public static function notify($receiver_id, $comment, $user, $article)
    {

        if (isset($comment['parent_comment_id']))
        {
            event(new CommentReply(
                $receiver_id, $user['name'], $user['avatar'], $article['id'], $article['title'], $comment['body']
            ));
        }
        else
        {
            event(new Comment(
                $receiver_id, $user['name'], $user['avatar'], $article['id'], $article['title'], $comment['body']
            ));
        }
    }

    public function comment() {
      return $this->belongsTo(Comment::class, 'new_comment');
    }
}
