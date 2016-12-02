
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
