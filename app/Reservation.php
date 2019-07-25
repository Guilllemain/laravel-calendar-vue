<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id', 'parking_number', 'date'
    ];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
