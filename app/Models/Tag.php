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

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function areasExpertise() {
        return $this->belongsToMany(User::class, 'area_of_expertise')->withPivot('reputation');
    }

    public function favoriteUsers() {
        return $this->belongsToMany(User::class, 'favorite_tag');
    }
}
