DO $$ BEGIN
CREATE TYPE direction AS ENUM ('in','out');
EXCEPTION
    WHEN duplicate_object THEN null;
END $$;

CREATE TABLE IF NOT EXISTS movements (
                                         id            bigserial PRIMARY KEY,
                                         person_id     bigint REFERENCES people(id) ON DELETE SET NULL,
    vehicle_id    bigint REFERENCES vehicles(id) ON DELETE SET NULL,
    checkpoint_id bigint REFERENCES checkpoints(id) ON DELETE SET NULL,
    direction     direction NOT NULL,
    entry_time    timestamptz,
    exit_time     timestamptz,
    created_at    timestamptz NOT NULL DEFAULT now()
    );
CREATE INDEX IF NOT EXISTS movements_entry_idx ON movements(entry_time);
CREATE INDEX IF NOT EXISTS movements_exit_idx ON movements(exit_time);
