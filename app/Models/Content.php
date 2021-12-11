<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    public $timestamps  = false;

    protected $table = 'content';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body', 'published_at', 'is_edited'
    ];

    public function author() {
        return $this->belongsTo(User::class);
    }

    public function feedback() {
        return $this->belongsToMany(User::class, 'feedback')->withPivot('is_like');
    }
}
