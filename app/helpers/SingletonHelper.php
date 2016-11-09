<?php

class SingletonHelper
{
    protected static $_instance;  // экземпляр объекта

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance()
    {
        if (empty(static::$_instance))
        {
            static::$_instance = new static();
        }
        return static::$_instance;
    }
}