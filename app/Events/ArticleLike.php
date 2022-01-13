<?php

namespace App\Events;

class ArticleLike extends Notification
{
    public $article_id;
    public $article_title;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($username, $avatar, $article_id, $article_title)
    {
        parent::__construct($username, $avatar);
        $this->article_id = $article_id;
        $this->article_title = $article_title;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'article-like';
    }
}
