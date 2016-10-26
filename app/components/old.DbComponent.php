<?php

/**
 * Class DbComponent
 *
 * Работа с БД через общий объект.
 */
class DbComponent
{
    private static $bdClass;

    public static function obj()
    {
        if (!self::$bdClass)
        {
            #require_once(APP_DIR . 'components/' . DATABASE_CLASS . '.php');
            $className = DATABASE_CLASS;
            self::$bdClass = new $className;
        }

        return self::$bdClass;
    }
}<?php

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
            #require_once(APP_DIR . 'components/' . DATABASE_CLASS . '.php');
            #$className = DATABASE_CLASS;
            #self::$bdClass = new $className;
            #self::$bdClass = new $className;
        }

        return self::$bdClass;
    }


}
