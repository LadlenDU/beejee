<?php

/*
CREATE DATABASE user_list CHARACTER SET cp1251;

CREATE TABLE users
(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(30) NOT NULL,
    `age` TINYINT unsigned,
    `city_id` int(11) unsigned,
    PRIMARY KEY (`id`)
) CHARACTER SET cp1251;

CREATE TABLE cities
(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(30) NOT NULL,
    PRIMARY KEY (`id`)
) CHARACTER SET cp1251;

ALTER TABLE `users` ADD CONSTRAINT `user_city_id` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`);

INSERT INTO `cities` SET name='Москва';
INSERT INTO `cities` SET name='Владивосток';
INSERT INTO `cities` SET name='Николаев';

*/

define('APP_DIR', dirname(__FILE__) . '/../app/');
require_once(APP_DIR . 'config.php');

if (DEBUG)
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
else
{
    error_reporting(0);
}

require_once(APP_DIR . 'controllers/User.php');

try
{
    $user = new UserController();
}
catch (Exception $e)
{
    if (DEBUG)
    {
        echo 'Произошла ошибка. Код: ' . $e->getCode() . '. Сообщение: ' . $e->getMessage() .
            '. Файл: ' . $e->getFile() . '. Строка: ' . $e->getFile() . '. Trace: ' . $e->getTraceAsString();
    }
    else
    {
        echo "Ошибка на сервере";
    }
}

