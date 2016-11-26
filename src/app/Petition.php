<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Petition extends Model
{
    protected $guarded = [];

    public function authors()
    {
        return $this->hasMany(Author::class);
    }

    public function signatures()
    {
        return $this->hasMany(Signature::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function statuses()
    {
        return $this->hasMany(PetitionStatus::class);
    }
}
