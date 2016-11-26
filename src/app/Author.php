<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $guarded = [];

    public function petition()
    {
        return $this->belongsTo(Petition::class);
    }
}
