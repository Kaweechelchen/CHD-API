<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Petition extends Model
{
    protected $guarded = [];

    public function authors()
    {
        return $this->hasMany(Authors::class);
    }

    public function signatures()
    {
        return $this->hasMany(Signatures::class);
    }

    public function events()
    {
        return $this->hasMany(Events::class);
    }

    public function statuses()
    {
        return $this->hasMany(PetitionStatuses::class);
    }
}
