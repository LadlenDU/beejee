<?php

class ConfigHelper
{
    protected $config;

    protected static $instance;

    protected function __construct()
    {
        require_once(APP_DIR . '/config/Common.php');
        $this->config = $GLOBALS['config'];
    }

    private function __clone()    { /* ... @return Singleton */ }  // Защищаем от создания через клонирование
    private function __wakeup()   { /* ... @return Singleton */ }  // Защищаем от создания через unserialize

    public static function getConfig()
    {
        if (empty(self::$instance))
        {
            self::$instance = new ConfigHelper;
        }
        return self::$instance->config;
    }
}

