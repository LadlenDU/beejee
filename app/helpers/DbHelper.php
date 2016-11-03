<?php

/**
 * Class DbHelper
 *
 * Работа с БД через общий объект.
 */
class DbHelper
{
    private static $bdClass;

    public static function obj()
    {
        if (!self::$bdClass)
        {
            assert(isset(ConfigComponent::getConfig()['database']['type']));

            $className = ConfigComponent::getConfig()['database']['type'] . 'DatabaseComponent';
            self::$bdClass = new $className();
        }

        return self::$bdClass;
    }

}
