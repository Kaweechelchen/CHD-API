<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    public function index()
    {
        $peopleToThank = [
            [
                'name' => 'Kürbiskär <i title="love" class="fa fa-heart red pulse" aria-hidden="true"></i>',
            ],
            [
                'name' => 'Daniel 🐵',
                'link' => 'https://twitter.com/dattn_',
            ],
            [
                'name' => 'Clawfire 🐻',
                'link' => 'https://twitter.com/Clawfire',
            ],
            [
                'name' => 'Fränz 🦄',
                'link' => 'https://twitter.com/ffraenz',
            ],
        ];

        return view(
            'about',
            compact(
                'peopleToThank'
            )
        );
    }
}
