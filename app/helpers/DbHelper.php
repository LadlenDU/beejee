<?php

/**
 * Class DbHelper
 *
 * Работа с БД через общий объект.
 */
class DbHelper
{
    protected static $bdClassInstance;

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    protected function __wakeup()
    {
    }

    public static function obj()
    {
        if (!self::$bdClassInstance)
        {
            $conf = ConfigHelper::getInstance()->getConfig();
            assert(isset($conf['database']['type']));

            $className = $conf['database']['type'] . 'DatabaseComponent';
            self::$bdClassInstance = new $className();
        }

        return self::$bdClassInstance;
    }

}
