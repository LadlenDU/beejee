<?php

namespace app\core;

/**
 * Config служит для работы с конфигурацией.
 *
 * @package app\core
 */
class Config extends Singleton
{
    protected $config;

    /**
     * Возвращает данные конфигурации.
     *
     * @return array массив с данными конфигурации.
     */
    public function getConfig()
    {
        if (!$this->config)
        {
            $this->config = require_once(APP_DIR . 'config/Common.php');
        }
        return $this->config;
    }
}

