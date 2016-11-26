<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    public function links()
    {
        return $this->hasMany(Link::class);
    }
}
