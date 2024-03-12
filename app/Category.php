<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function pictures()
    {
        return $this->belongsTo('App\Picture');
    }
}
