<?php

namespace App\Http\Controllers;

use App\Statuses;

class PetitionsController extends Controller
{
    public function index()
    {
        return Statuses::all();
    }
}
