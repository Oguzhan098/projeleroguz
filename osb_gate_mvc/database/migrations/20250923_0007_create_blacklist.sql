CREATE TABLE IF NOT EXISTS blacklist (
                                         id            bigserial PRIMARY KEY,
                                         person_id     bigint REFERENCES people(id) ON DELETE CASCADE,
    vehicle_id    bigint REFERENCES vehicles(id) ON DELETE CASCADE,
    reason        text,
    active        boolean NOT NULL DEFAULT true,
    created_at    timestamptz NOT NULL DEFAULT now()
    );
