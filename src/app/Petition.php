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

            ORDER BY
                petitions.number DESC
            LIMIT ?
            OFFSET ?',
            [
                $limit,
                $offset,
            ]
        );
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
