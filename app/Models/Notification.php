<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps  = false;

    protected $table = 'notification';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reveiver_id', 'date',
    ];

    public function receiver() {
        return $this->belongsTo(User::class);
    }
}

/*
TODO: Restrictions on the notification parameters between the different models
Maybe use policies
*/
