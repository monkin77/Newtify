<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Content
{
  protected $table = 'article';

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
            // TODO: Try with Content::class instead of 'content'
            $query->join('content', 'content_id', '=', 'id');
        });
    }

    public function content() {
      return $this->belongsTo(Content::class);
    }

    public function article_tags() {
      return $this->belongsToMany(Tag::class);
    }
}
