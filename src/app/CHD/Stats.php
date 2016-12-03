<?php

namespace App\CHD;

use App\Signature;
use App\SignatureStats;
use Illuminate\Support\Facades\DB;

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
                        'label'  => strtotime('-'.($hour + $offsetHours + 1).' hours'),
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
        $dayStats['total'] = 0;
        $DBdayStats        = SignatureStats::where('unit', 'day')
            ->where('scope', 'global')
            ->orderBy('delta')
            ->select('count')
            ->get();

        foreach ($DBdayStats as $key => $dayStat) {
            $dayStats['days'][] = $dayStat->count;
            $dayStats['total'] += $dayStat->count;
        }

        return $dayStats;
    }

    public function hours()
    {
        $hourStats['total'] = 0;
        $DBhourStats        = SignatureStats::where('unit', 'hour')
            ->where('scope', 'global')
            ->orderBy('delta')
            ->select('count')
            ->get();

        foreach ($DBhourStats as $key => $hourStat) {
            $hourStats['days'][] = $hourStat->count;
            $hourStats['total'] += $hourStat->count;
        }

        return $hourStats;
    }

    public function petitionSignaturesByDay($petitionId = null, $daysAgo = null)
    {
        $query = Signature::select(
                DB::raw('ANY_VALUE(DATEDIFF(now(), created_at)) AS days_ago'),
                DB::raw('ANY_VALUE(petition_id) AS petition_id'),
                DB::raw('ANY_VALUE(COUNT(*)) AS count')
            )
            ->groupBy('days_ago')
            ->groupBy('petition_id');

        if ($petitionId !== null) {
            $query = $query->where('petition_id', $petitionId);
        }

        if ($daysAgo !== null) {
            $query = $query->whereRaw(
                'DATEDIFF(now(), created_at) = ?',
                [
                    $daysAgo,
                ]
            );
        }

        if ($query->value('count') === null) {
            return 0;
        }

        return $query->value('count');
    }
}
