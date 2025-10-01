CREATE TABLE IF NOT EXISTS instructors (
                                           id SERIAL PRIMARY KEY,
                                           first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
    );


CREATE TABLE IF NOT EXISTS courses (
                                       id SERIAL PRIMARY KEY,
                                       code VARCHAR(20) UNIQUE NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    instructor_id INT REFERENCES instructors(id) ON DELETE SET NULL,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
    );


CREATE TABLE IF NOT EXISTS students (
                                        id SERIAL PRIMARY KEY,
                                        first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    registered_at DATE DEFAULT CURRENT_DATE,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
    );


CREATE TABLE IF NOT EXISTS enrollments (
                                           id SERIAL PRIMARY KEY,
                                           student_id INT NOT NULL REFERENCES students(id) ON DELETE CASCADE,
    course_id INT NOT NULL REFERENCES courses(id) ON DELETE CASCADE,
    enrolled_at TIMESTAMPTZ DEFAULT NOW(),
    grade NUMERIC(5,2),
    UNIQUE(student_id, course_id)
    );

CREATE TABLE IF NOT EXISTS custodians (
                                        id SERIAL PRIMARY KEY,
                                        first_name VARCHAR(100) NOT NULL,
                                        last_name VARCHAR(100) NOT NULL,
                                        student_id INT REFERENCES students(id) ON DELETE SET NULL,
                                        registered_at DATE DEFAULT CURRENT_DATE,
                                        created_at TIMESTAMPTZ DEFAULT NOW(),
                                        updated_at TIMESTAMPTZ DEFAULT NOW()
);


CREATE TABLE IF NOT EXISTS achievements (
                                            id           SERIAL PRIMARY KEY,
                                            first_name   VARCHAR(120) NOT NULL,
                                            last_name    VARCHAR(120) NOT NULL,
                                            registered_at TIMESTAMPTZ NULL,
                                            created_at   TIMESTAMPTZ NOT NULL DEFAULT NOW(),
                                            updated_at   TIMESTAMPTZ NOT NULL DEFAULT NOW()
);


CREATE OR REPLACE FUNCTION set_updated_at()
    RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_achievements_updated_at ON achievements;
CREATE TRIGGER trg_achievements_updated_at
    BEFORE UPDATE ON achievements
    FOR EACH ROW EXECUTE FUNCTION set_updated_at();


CREATE EXTENSION IF NOT EXISTS pg_trgm;
CREATE INDEX IF NOT EXISTS idx_ach_first_name_trgm ON achievements USING GIN (first_name gin_trgm_ops);
CREATE INDEX IF NOT EXISTS idx_ach_last_name_trgm  ON achievements USING GIN (last_name  gin_trgm_ops);






-- Faydalı indexler
CREATE INDEX IF NOT EXISTS idx_courses_instructor ON courses(instructor_id);
CREATE INDEX IF NOT EXISTS idx_enrollments_student ON enrollments(student_id);
CREATE INDEX IF NOT EXISTS idx_enrollments_course ON enrollments(course_id);