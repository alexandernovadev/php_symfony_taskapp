CREATE DATABASE IF NOT EXISTS symfonytask;
USE symfonytask;

CREATE TABLE users(
id          int(255) auto_increment not null,
role        varchar(50) not null,
name        varchar(150),
surname     varchar(255) not null,
email       varchar(255) not null,
password    varchar(255),
created_at  datetime DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT pk_users PRIMARY KEY(id)
)ENGINE=InnoDb;

-- INSERT INTO `users` (`id`, `role`, `name`, `surname`, `email`, `password`, `created_at`) VALUES (NULL, 'ROLE_USER', 'Alexander', 'Nova', 'alex@alex.com', 'alex', CURRENT_TIMESTAMP), (NULL, 'ROLE_USER', 'Tito', 'Arevalo', 'tito@tito.com', 'tito', CURRENT_TIMESTAMP)

CREATE TABLE tasks(
id           int(255) auto_increment not null,
user_id      int(255) not null,
title        varchar(255) not null,
content      text,
priority     varchar(255) not null,
hours        int(100),
created_at   datetime DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT pk_videos PRIMARY KEY(id),
CONSTRAINT fk_taks_user FOREIGN KEY(user_id) REFERENCES users(id)
)ENGINE=InnoDb;