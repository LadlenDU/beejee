<?php

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

require_once(APP_DIR . 'controllers/Comments.php');

try
{
    $comments = new CommentsController();
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
        echo _('Ошибка на сервере');
    }
}

