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



-- Faydalı indexler
CREATE INDEX IF NOT EXISTS idx_courses_instructor ON courses(instructor_id);
CREATE INDEX IF NOT EXISTS idx_enrollments_student ON enrollments(student_id);
CREATE INDEX IF NOT EXISTS idx_enrollments_course ON enrollments(course_id);