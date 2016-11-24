<?php

class SingletonHelper
{
    private static $instances = [];

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new Exception('Cannot unserialize singleton');
    }

    public static function getInstance()
    {
        $cls = get_called_class();
        if (!isset(self::$instances[$cls]))
        {
            self::$instances[$cls] = new static;
        }
        return self::$instances[$cls];
    }
}