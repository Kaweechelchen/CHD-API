
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

-- GET the latest status for all petitions
SELECT
    p.*,
    s.status,
    ps.created_at AS status_update
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

    INNER JOIN statuses s
        ON s.id = ps.status_id
    INNER JOIN petitions p
        ON p.id = ps.petition_id
;

-- all signatures from the last 7 days
SELECT
    COUNT(*) AS

FROM
    signatures

WHERE
    created_at >= (DATE_SUB(NOW(), INTERVAL 7 DAY))
;

-- all signatures from the last day
SELECT
    *

FROM
    signatures

WHERE
    created_at >= (DATE_SUB(NOW(), INTERVAL 1 DAY))
;


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




    SELECT
        petitions.number,
        petition_statuses.status_id,
        petition_statuses.created_at

    FROM
        petitions
        INNER JOIN petition_statuses
            ON petitions.id = petition_statuses.petition_id

        WHERE
            status_id = 4


    SELECT
        DATE(created_at) Date,
        petition_id,
        COUNT(*) totalCOunt

    FROM
        signatures

    WHERE
        petition_id = 807

    GROUP BY
        DATE(created_at),
        petition_id
    ;

    SELECT
        *

    FROM (
        SELECT
            ANY_VALUE(DATEDIFF(now(), created_at)) AS days_ago,
            ANY_VALUE(petition_id),
            ANY_VALUE(COUNT(id)) AS num_texts

        FROM
            signatures

        GROUP BY
            DATE(created_at),
            petition_id

        ) AS temp
    ;


    SELECT
        DATE_FORMAT(created_at, '%m/%d/%Y'),
        COUNT(*)

    FROM
        signatures

    WHERE
        created_at BETWEEN NOW() - INTERVAL 30 DAY AND NOW()

    GROUP BY
        DATE_FORMAT(created_at, '%m/%d/%Y')
    ;
