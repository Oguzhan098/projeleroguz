CREATE TABLE IF NOT EXISTS checkpoints (
                                           id            bigserial PRIMARY KEY,
                                           name          text NOT NULL,
                                           location      text,
                                           created_at    timestamptz NOT NULL DEFAULT now(),
    updated_at    timestamptz NOT NULL DEFAULT now()
    );
INSERT INTO checkpoints(name) VALUES ('Ana Kapı') ON CONFLICT DO NOTHING;
