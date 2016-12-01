SELECT number, ANY_VALUE(status), COUNT(number), MAX(created_at) FROM (
    SELECT
        ps.created_at,
        p.number,
        s.status

    FROM
        petitions p
        INNER JOIN petition_statuses ps
            ON ps.petition_id = p.id
        INNER JOIN statuses s
            ON ps.status_id = s.id
) AS data
GROUP BY
    number;



    SELECT
        ANY_VALUE(ps.created_at) created,
        ANY_VALUE(ps.status_id) statusId,
        p.number

    FROM
        petitions p
        INNER JOIN petition_statuses ps
            ON ps.petition_id = p.id

    GROUP BY
        number,
        created,
        statusId

    HAVING
        MAX(created) = created
    ;




--SELECT number, ANY_VALUE(status), COUNT(number), MAX(created_at) FROM (



SELECT
    ANY_VALUE(MAX(ps.created_at)) as status_update,
    ANY_VALUE(ps.status_id),
    p.number

FROM
    petitions p
    INNER JOIN petition_statuses ps
        ON ps.petition_id = p.id

GROUP BY
    number

ORDER BY number DESC
LIMIT 10
;



HAVING
    ps.created_at = MAX(ps.created_at)



SELECT
    n.*

FROM
    tblpm n
    INNER JOIN (
        SELECT
            control_number,
            MAX(date_updated) AS date_updated

        FROM
            tblpm

        GROUP BY
            control_number
    ) AS max

USING (
    control_number,
    date_updated
);

-- Get the 10 last signatures
SELECT
    petition_id,
    created_at

FROM
    signatures

ORDER BY
    created_at DESC

LIMIT 10;

-- GET the latest status for all petitions
SELECT
    ps.*
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
);

-- GET Last 10 signatures and group them by petition_id
SELECT
    petition_id,
    COUNT(petition_id),
    MAX(created_at)

FROM (
    SELECT
        petition_id,
        created_at

    FROM
        signatures

    ORDER BY
        created_at DESC

    LIMIT 10
) AS data

GROUP BY
    petition_id;
