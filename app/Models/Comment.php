<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Content
{
  protected $table = 'comment';

  protected $primaryKey = 'content_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public static function boot()
    {
        parent::boot();

        // All the queries are joined with the content table
        static::addGlobalScope(function ($query) {
            $query->join('content', 'content_id', '=', 'id');
        });
    }

    public function article() {
      return $this->belongsTo(Article::class, 'article_id');
    }

    public function content() {
      return $this->belongsTo(Content::class);
    }

    public function parent_comment() {
      return $this->belongsTo(Comment::class, 'parent_comment_id');
    } 

    public function child_comments() {
      return $this->hasMany(Comment::class, 'parent_comment_id');
    }

    public function notification() {
      return $this->hasOne(CommentNotification::class, 'new_comment');
    }

    public function getInfo() {
      $published_at = date('F j, Y', strtotime( $this['published_at'] ) ) ;  

      return [
          'id' => $this->content_id, 
          'body' => $this->body,
          'likes' => $this->likes,
          'dislikes' => $this->dislikes,
          'published_at' =>$published_at,
          'author' => isset($this->author) ? [
              'id' => $this->author->id,
              'name' => $this->author->name,
              'avatar' => $this->author->avatar,
          ] : null,
      ];
    }
}
