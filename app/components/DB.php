<?php

/**
 * Class DB
 *
 * Работа с БД через общий объект.
 */
class DB
{
    private static $bdClass;

    public static function obj()
    {
        if (!self::$bdClass)
        {
            require_once(APP_DIR . 'components/' . DATABASE_CLASS . '.php');
            $className = DATABASE_CLASS;
            self::$bdClass = new $className;
        }

        return self::$bdClass;
    }
}