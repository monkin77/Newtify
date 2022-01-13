<?php

namespace App\Events;

class CommentLike extends Notification
{
    public $article_id;
    public $comment_body;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($receiver_id, $username, $avatar, $article_id, $comment_body)
    {
        parent::__construct($receiver_id, $username, $avatar);
        $this->article_id = $article_id;
        $this->comment_body = $comment_body;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'comment-like';
    }
}
