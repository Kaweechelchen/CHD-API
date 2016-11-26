<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PetitionStatus extends Model
{
    protected $guarded = [];

    public function petition()
    {
        return $this->belongsTo(Petition::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
