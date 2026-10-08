-- Schema do projeto Club Hawkings
-- Banco: clubhawkings

CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS alunos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    nasc DATE,
    turma VARCHAR(50) NOT NULL,
    ativo BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS produtos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    estoque INTEGER NOT NULL DEFAULT 0,
    preco NUMERIC(10,2) NOT NULL DEFAULT 0.00,
    imagem VARCHAR(255) NOT NULL
);

-- Conta de teste: admin@clubhawkings.com / 123456
INSERT INTO usuarios (email, senha)
VALUES ('admin@clubhawkings.com', '$2y$12$YpMoiHZDf1ECUxEtsyHYhOwGLy6woqzs4rcsDReOCxqTOscGjxhXG')
ON CONFLICT (email) DO NOTHING;
