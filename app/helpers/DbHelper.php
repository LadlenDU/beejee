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
            assert(isset(self::$config));
            assert(isset(self::$config['database']['type']));

            $className = self::$config['database']['type'] . 'databaseComponent';
            self::$bdClass = new $className(self::$config);
        }

        return self::$bdClass;
    }

}
