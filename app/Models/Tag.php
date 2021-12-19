<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps  = false;

    protected $table = 'tag';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'state',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function areasExpertise()
    {
        return $this->belongsToMany(User::class, 'area_of_expertise')->withPivot('reputation');
    }

    public function favoriteUsers()
    {
        return $this->belongsToMany(User::class, 'favorite_tag');
    }

    public function articleTags()
    {
        return $this->belongsToMany(Article::class, 'article_tag', 'tag_id', 'article_id');
    }

    /**
     * Returns a list of Accepted Tags
     *
     * @return List of accepted tags
     */
    public static function listAcceptedTags()
    {
        $tags = Tag::where('state', 'ACCEPTED')->get()->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name
            ];
        });
        return $tags;
    }

    /**
     * Checks if a user already has a tag as favorite
     * @return Bool
     */
    public function isFavorite($user_id)
    {
        $favoriteList = $this->favoriteUsers->where('id', $user_id);
        return count($favoriteList) > 0;
    }
}
