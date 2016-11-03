<?php

class ConfigComponent
{
    protected $config;

    protected static $item;

    public function __construct()
    {
        require_once(APP_DIR . '/config/Common.php');
        $this->config = $GLOBALS['config'];
    }

    public static function getConfig()
    {
        if (empty(self::$item))
        {
            self::$item = new ConfigComponent;
        }
        return self::$item->config;
    }
}

