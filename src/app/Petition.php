<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public static function includingStatus($limit = null, $offset = null)
    {
        $limit  = ($limit === null) ? 10 : $limit;
        $offset = ($offset === null) ? 0 : $offset;

        $petitions = DB::select(
            'SELECT
                petitions.*,
                statuses.status,
                ps.created_at AS status_updated_at
            FROM
                petition_statuses ps
                INNER JOIN (
                    SELECT
                        petition_id,
                        MAX(created_at) AS created_at

                    FROM
                        petition_statuses

                    GROUP BY
                        petition_id
                ) AS max

                USING (
                    petition_id,
                    created_at
                )

                INNER JOIN statuses
                    ON ps.status_id = statuses.id
                INNER JOIN petitions
                    ON ps.petition_id = petitions.id

            ORDER BY
                petitions.number ASC
            LIMIT ?
            OFFSET ?',
            [
                $limit,
                $offset,
            ]
        );

        foreach ($petitions as &$petition) {
            $signature_count = Signature::where('petition_id', $petition->id)
                ->count();

            $petition->signature_count = $signature_count;
        }

        return $petitions;
    }

    public static function includingStatusAndStats($limit = null, $offset = null)
    {
        $limit  = ($limit === null) ? 10 : $limit;
        $offset = ($offset === null) ? 0 : $offset;

        $petitions = DB::select(
            'SELECT
                petitions.*,
                statuses.status,
                ps.created_at AS status_updated_at,
                signature_stats.compiled AS stats
            FROM
                petition_statuses ps
                INNER JOIN (
                    SELECT
                        petition_id,
                        MAX(created_at) AS created_at

                    FROM
                        petition_statuses

                    GROUP BY
                        petition_id
                ) AS max

                USING (
                    petition_id,
                    created_at
                )

                INNER JOIN statuses
                    ON ps.status_id = statuses.id
                INNER JOIN petitions
                    ON ps.petition_id = petitions.id
                INNER JOIN signature_stats
                    ON ps.petition_id = signature_stats.label
                    AND signature_stats.scope = "petition"

            ORDER BY
                petitions.number DESC
            LIMIT ?
            OFFSET ?',
            [
                $limit,
                $offset,
            ]
        );

        foreach ($petitions as &$petition) {
            $signature_count = Signature::where('petition_id', $petition->id)
                ->count();

            $petition->signature_count = $signature_count;
        }

        return $petitions;
    }

    public static function withStatus($status, $limit = null, $offset = null)
    {
        $limit  = ($limit === null) ? 10 : $limit;
        $offset = ($offset === null) ? 0 : $offset;

        return DB::select(
            'SELECT
                petitions.*,
                statuses.status,
                ps.created_at AS status_updated_at
            FROM
                petition_statuses ps
                INNER JOIN (
                    SELECT
                        petition_id,
                        MAX(created_at) AS created_at

                    FROM
                        petition_statuses

                    GROUP BY
                        petition_id
                ) AS max

                USING (
                    petition_id,
                    created_at
                )

                INNER JOIN statuses
                    ON ps.status_id = statuses.id
                INNER JOIN petitions
                    ON ps.petition_id = petitions.id

            WHERE
                statuses.id = ?

            ORDER BY
                petitions.number DESC
            LIMIT ?
            OFFSET ?',
            [
                $status,
                $limit,
                $offset,
            ]
        );
    }
}
