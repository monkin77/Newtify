<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Content
{
  protected $table = 'comment';

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
            // TODO: Try with Content::class instead of 'content'
            $query->join('content', 'content_id', '=', 'id');
        });
    }

    public function content() {
      return $this->belongsTo(Content::class);
    }

    public function notification() {
      return $this->hasOne(CommentNotification::class, 'new_comment');
    }
}
