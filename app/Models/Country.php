<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps  = false;

    protected $table = 'country';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name',
    ];

    public function citizens() {
        return $this->hasMany(User::class);
    }

    public static function getIdByName($name) {
        return Country::where('name', $name)->first()->id;
    }
}
