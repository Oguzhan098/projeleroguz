CREATE TABLE IF NOT EXISTS roles (
                                     id    bigserial PRIMARY KEY,
                                     name  text UNIQUE NOT NULL
);
CREATE TABLE IF NOT EXISTS users (
                                     id         bigserial PRIMARY KEY,
                                     email      text UNIQUE NOT NULL,
                                     password   text NOT NULL,
                                     full_name  text,
                                     created_at timestamptz NOT NULL DEFAULT now()
    );
CREATE TABLE IF NOT EXISTS user_roles (
                                          user_id bigint REFERENCES users(id) ON DELETE CASCADE,
    role_id bigint REFERENCES roles(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, role_id)
    );
INSERT INTO roles(name) VALUES ('admin'), ('security'), ('company') ON CONFLICT DO NOTHING;
