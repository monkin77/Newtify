<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Content
{
  protected $table = 'article';

  protected $primaryKey = 'content_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'thumbnail',
    ];

    public static function boot()
    {
        parent::boot();

        // All the queries are joined with the content table
        static::addGlobalScope(function ($query) {
            $query->join('content', 'content_id', '=', 'id');
        });
    }

    public function content() {
      return $this->belongsTo(Content::class);
    }

    public function comments() {
      return Comment::where('article_id', $this->content_id)->get();
    }

    public function articleTags() {
      return $this->belongsToMany(Tag::class, 'article_tag', 'article_id', 'tag_id');
    }
}
