CREATE DATABASE IF NOT EXISTS beejee
  CHARACTER SET utf8;

USE beejee;

DROP TABLE IF EXISTS user_role;
DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS role;
-- DROP TABLE IF EXISTS image;

-- CREATE TABLE image
-- (
--   `id`      INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
--  `type`    VARCHAR(5)       NOT NULL,
--  `width`   INT(11)          NOT NULL,
--  `height`  INT(11)          NOT NULL,
--   `content` MEDIUMBLOB,
--   PRIMARY KEY (`id`)
-- );

CREATE TABLE comment
(
  `id`         INT(11) UNSIGNED                           NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(150)                               NOT NULL DEFAULT 'Anonimous',
  `email`      VARCHAR(255),
  `text`       TEXT,
  --  `image_id` INT(11) UNSIGNED                           NULL,
  `image_name` VARCHAR(100)                               NULL,
  `status`     ENUM ('UNDEFINED', 'REJECTED', 'APPROVED') NOT NULL DEFAULT 'UNDEFINED',
  `created`    TIMESTAMP                                  NOT NULL,
  `modified`   TIMESTAMP                                  NULL,
  --  FOREIGN KEY (`image_id`) REFERENCES `image` (`id`)
  --    ON DELETE SET NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE user
(
  `id`            INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `login`         VARCHAR(150)     NOT NULL,
  `password_hash` VARCHAR(255)     NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`login`)
);

CREATE TABLE role
(
  `id`   INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150)     NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE user_role
(
  `user_id` INT(11) UNSIGNED NOT NULL,
  `role_id` INT(11) UNSIGNED NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
    ON DELETE CASCADE,
  FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
    ON DELETE CASCADE,
  PRIMARY KEY (`user_id`, `role_id`)
);

INSERT INTO `user`
SET login = 'admin', password_hash = PASSWORD('123');

INSERT INTO `role`
SET name = 'admin';

INSERT INTO `user_role`
SET user_id = (SELECT `id`
               FROM `user`
               WHERE `login` = 'admin'),
  role_id   = (SELECT `id`
               FROM `role`
               WHERE `name` = 'admin');
