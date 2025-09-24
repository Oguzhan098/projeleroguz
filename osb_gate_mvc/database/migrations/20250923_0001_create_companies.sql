CREATE TABLE IF NOT EXISTS companies (
                                         id            bigserial PRIMARY KEY,
                                         name          text NOT NULL,
                                         code          text UNIQUE,
                                         created_at    timestamptz NOT NULL DEFAULT now(),
    updated_at    timestamptz NOT NULL DEFAULT now()
    );
