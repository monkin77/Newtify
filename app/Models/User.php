<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $table = 'authenticated_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'birth_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function followers() {
        return $this->belongsToMany(User::class, 'follow', 'followed_id', 'follower_id');
    }

    public function following() {
        return $this->belongsToMany(User::class, 'follow', 'follower_id', 'followed_id');
    }

    // verify if foreign key is right (default should be '<Model>_id')
    public function suspensions() {
        return $this->hasMany(Suspension::class);
    }

    public function reports() {
        return $this->hasMany(Report::class, 'reported_id');
    }

    public function givenReports() {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function proposedTags() {
        return $this->hasMany(Tag::class);
    }

    // Access area of expertise with area->pivot
    public function areasExpertise() {
        return $this->belongsToMany(Tag::class, 'area_of_expertise')->withPivot('reputation');
    }

    public function favoriteTags() {
        return $this->belongsToMany(Tag::class, 'favorite_tag');
    }

    public function sentMessages() {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages() {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
