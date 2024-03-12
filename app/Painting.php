<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Painting extends Model
{
    use SoftDeletes;

    public function category()
    {
        return $this->belongsTo('App\Category', 'category', 'id');
    }

    public function artist()
    {
        return $this->belongsTo('App\Artist', 'artist', 'id');
    }
}
