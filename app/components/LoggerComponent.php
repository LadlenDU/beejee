<?php

class LoggerComponent
{
    private static $instance;  // экземпляр объекта

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
        if (empty(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function log($message)
    {
        assert(ConfigComponent::getConfig()['log']['filePath']);

        $fPath = ConfigComponent::getConfig()['log']['filePath'];
        if (is_writable($fPath))
        {
            $message .= PHP_EOL . PHP_EOL . '++++++++++++++++++++++++++++++++++++++++++++' . PHP_EOL . PHP_EOL . PHP_EOL;
            file_put_contents($fPath, $message, FILE_APPEND);
        }
    }
}
