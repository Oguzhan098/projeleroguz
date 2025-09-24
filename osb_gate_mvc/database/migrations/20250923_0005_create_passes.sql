CREATE TABLE IF NOT EXISTS passes (
                                      id            bigserial PRIMARY KEY,
                                      person_id     bigint REFERENCES people(id) ON DELETE CASCADE,
    vehicle_id    bigint REFERENCES vehicles(id) ON DELETE CASCADE,
    card_uid      text,
    qr_code       text,
    valid_from    timestamptz,
    valid_to      timestamptz,
    active        boolean NOT NULL DEFAULT true,
    created_at    timestamptz NOT NULL DEFAULT now(),
    updated_at    timestamptz NOT NULL DEFAULT now(),
    UNIQUE(person_id, vehicle_id)
    );
