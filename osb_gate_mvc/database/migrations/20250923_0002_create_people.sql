CREATE TABLE IF NOT EXISTS people (
                                      id            bigserial PRIMARY KEY,
                                      company_id    bigint REFERENCES companies(id) ON DELETE SET NULL,
    full_name     text NOT NULL,
    national_id   text,
    created_at    timestamptz NOT NULL DEFAULT now(),
    updated_at    timestamptz NOT NULL DEFAULT now()
    );
CREATE INDEX IF NOT EXISTS people_company_idx ON people(company_id);
