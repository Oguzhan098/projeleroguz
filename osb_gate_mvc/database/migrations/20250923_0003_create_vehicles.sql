CREATE TABLE IF NOT EXISTS vehicles (
                                        id            bigserial PRIMARY KEY,
                                        plate         text UNIQUE NOT NULL,
                                        type          text,
                                        owner_person_id bigint REFERENCES people(id) ON DELETE SET NULL,
    created_at    timestamptz NOT NULL DEFAULT now(),
    updated_at    timestamptz NOT NULL DEFAULT now()
    );
