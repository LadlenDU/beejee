<?php

class ConfigHelper extends SingletonHelper
{
    protected $config;

    protected $hasAutoloader;

    public function getConfig()
    {
        if (!$this->config)
        {
            $this->config = require_once(APP_DIR . 'config/Common.php');
        }

        if (!$this->hasAutoloader && function_exists('__autoload'))
        {
            $this->hasAutoloader = true;
            $this->config = array_merge_recursive($this->config, require_once(APP_DIR . 'config/CommonRequireAutoload.php'));
        }

        return $this->config;
    }
}

