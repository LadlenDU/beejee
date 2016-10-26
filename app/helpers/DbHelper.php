<?php

/**
 * Class DbHelper
 *
 * Работа с БД через общий объект.
 */
class DbHelper
{
    private static $bdClass;

    private static $config;

    public static function setConfig($config)
    {
        self::$config = $config;
    }

    public static function obj()
    {
        if (!self::$bdClass)
        {
            #require_once(APP_DIR . 'components/' . DATABASE_CLASS . '.php');
            #$className = DATABASE_CLASS;
            #self::$bdClass = new $className;
            assert(isset(self::$config));
            assert(isset(self::$config['database']['type']));

            $className = self::$config['database']['type'] . 'DatabaseComponent';
            self::$bdClass = new $className;
        }

        return self::$bdClass;
    }

}
