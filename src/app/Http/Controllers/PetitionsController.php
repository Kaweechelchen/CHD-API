<?php

namespace App\Http\Controllers;

class PetitionsController extends Controller
{
    public function index()
    {
        dd(app('Request')->get('https://mona.lu'));
    }
}
