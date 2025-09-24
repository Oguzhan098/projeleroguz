CREATE TABLE IF NOT EXISTS schedules (
                                         id            bigserial PRIMARY KEY,
                                         company_id    bigint REFERENCES companies(id) ON DELETE CASCADE,
    checkpoint_id bigint REFERENCES checkpoints(id) ON DELETE CASCADE,
    day_of_week   int2 NOT NULL CHECK (day_of_week BETWEEN 0 AND 6), -- 0 Pazar
    start_time    time NOT NULL,
    end_time      time NOT NULL
    );
