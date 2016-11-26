<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $guarded = [];

    public function petitions()
    {
        return $this->hasMany(PetitionStatus::class);
    }
}
