<?php

class LoggerComponent
{
    private static $_instance;  // экземпляр объекта

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
        if (empty(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function log($message)
    {
        assert(ConfigHelper::getConfig()['log']['filePath']);

        $fPath = ConfigHelper::getConfig()['log']['filePath'];
        if (is_writable($fPath))
        {
            $message .= PHP_EOL . PHP_EOL . '++++++++++++++++++++++++++++++++++++++++++++' . PHP_EOL . PHP_EOL . PHP_EOL;
            file_put_contents($fPath, $message, FILE_APPEND);
        }
    }
}
