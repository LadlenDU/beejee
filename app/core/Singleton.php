<?php

namespace app\core;

/**
 * Singleton помогает превратить класс в singleton.
 *
 * @package app\core
 */
abstract class Singleton
{
    private static $_instances = [];

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new Exception(_('Cannot unserialize singleton'));
    }

    /**
     * Возвращает экземпляр синглтона.
     *
     * @return mixed
     */
    public static function inst()
    {
        $cls = get_called_class();
        if (!isset(self::$_instances[$cls]))
        {
            self::$_instances[$cls] = new static;
        }
        return self::$_instances[$cls];
    }
}