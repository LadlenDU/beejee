<?php

// Префикс класса с интерфейсом DatabaseOperations для обработки запросов к БД.
define('DATABASE', 'mySqli');

// Данные соединения.
define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'root');
define('MYSQL_PASS', 'temp123');
define('MYSQL_DB', 'beejee');

define('DATABASE_CLASS', DATABASE . 'DatabaseOperations');

define('DOCUMENT_ENCODING', 'utf-8');

// Переключение отладочного режима.
define('DEBUG', true);