<?php

namespace App\CHD;

use App\Signature;
use App\SignatureStats;

class Stats
{
    protected $maxDays = 7;

    public function init()
    {
        $this->global();
    }

    protected function global()
    {
        $offsetHours = 0;

        for ($day = 1; $day <= $this->maxDays; ++$day) {
            $dayCount = 0;

            $offsetHours = 24 * ($day - 1);

            for ($hour = 1; $hour <= 24; ++$hour) {
                $count = Signature::whereBetween(
                    'created_at', [
                        date('Y-m-d H:i:s', strtotime('-'.($hour + $offsetHours + 1).' hours')),
                        date('Y-m-d H:i:s', strtotime('-'.($hour + $offsetHours).' hours')),
                    ]
                )
                ->where('created_at', '>', env('FIRST_SCRAPE_END'))
                ->count();

                $dayCount += $count;

                SignatureStats::updateOrCreate(
                    [
                        'scope'  => 'global',
                        'unit'   => 'hour',
                        'delta'  => $hour + $offsetHours,
                    ],
                    [
                        'scope'  => 'global',
                        'unit'   => 'hour',
                        'delta'  => $hour + $offsetHours,
                        'count'  => $count,
                    ]
                );
            }

            SignatureStats::updateOrCreate(
                [
                    'scope'  => 'global',
                    'unit'   => 'day',
                    'delta'  => $day,
                ],
                [
                    'scope'  => 'global',
                    'unit'   => 'day',
                    'delta'  => $day,
                    'count'  => $dayCount,
                ]
            );
        }
    }

    public function days()
    {
        return SignatureStats::where('unit', 'day')
            ->where('scope', 'global')
            ->orderBy('delta')
            ->get();
    }

    public function hours()
    {
        return SignatureStats::where('unit', 'hour')
            ->where('scope', 'global')
            ->orderBy('delta')
            ->get();
    }
}
