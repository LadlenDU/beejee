<?php

class ConfigHelper extends SingletonHelper
{
    protected $config;

    protected function __construct()
    {
        require_once(APP_DIR . '/config/Common.php');
        $this->config = $GLOBALS['config'];
    }

    public function getConfig()
    {
        return $this->config;
    }
}

