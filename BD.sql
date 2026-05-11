    CREATE DATABASE BD_allQuizz

USE BD_allQuizz


CREATE TABLE tb_users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    nome     VARCHAR(100) NOT NULL,
    email    VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tipo     ENUM('admin', 'prof', 'aluno') NOT NULL
)

CREATE TABLE tb_disciplines (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
)

CREATE TABLE tb_themes (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    disciplina_id  INT NOT NULL,
    nome           VARCHAR(255) NOT NULL,
    CONSTRAINT fk_themes_disciplina FOREIGN KEY (disciplina_id) REFERENCES tb_disciplines(id) ON DELETE CASCADE ON UPDATE CASCADE
)

CREATE TABLE tb_questions (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    enunciado     TEXT NOT NULL,
    respA         VARCHAR(255) NOT NULL,
    respB         VARCHAR(255) NOT NULL,
    respC         VARCHAR(255) NOT NULL,
    respD         VARCHAR(255) NOT NULL,
    correta       CHAR(1) NOT NULL CHECK (correta IN ('A','B','C','D')),
    disciplina_id INT NOT NULL,
    tema_id       INT NOT NULL,
    imagem        VARCHAR(255) DEFAULT NULL,
    dificuldade   TINYINT NOT NULL CHECK (dificuldade BETWEEN 1 AND 5),
    CONSTRAINT fk_questions_disciplina FOREIGN KEY (disciplina_id) REFERENCES tb_disciplines(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_questions_tema FOREIGN KEY (tema_id) REFERENCES tb_themes(id) ON DELETE RESTRICT ON UPDATE CASCADE
)


CREATE TABLE tb_attempts (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    user_id   INT NOT NULL,
    data      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    pontuacao INT NOT NULL DEFAULT 0,
    tempo     INT NOT NULL DEFAULT 0, 
    CONSTRAINT fk_attempts_user FOREIGN KEY (user_id) REFERENCES tb_users(id) ON DELETE CASCADE ON UPDATE CASCADE
)

CREATE TABLE tb_attempt_answers (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    attempt_id    INT NOT NULL,
    question_id   INT NOT NULL,
    resposta_dada CHAR(1) NOT NULL CHECK (resposta_dada IN ('A','B','C','D')),
    correta       BOOLEAN NOT NULL DEFAULT 0,
    CONSTRAINT fk_answers_attempt FOREIGN KEY (attempt_id) REFERENCES tb_attempts(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_answers_question FOREIGN KEY (question_id) REFERENCES tb_questions(id) ON DELETE RESTRICT ON UPDATE CASCADE
)