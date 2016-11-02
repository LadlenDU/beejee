<?php

class LoggerComponent
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function log($message)
    {
        if (is_writable($this->config['log']['filePath']))
        {
            $message .= PHP_EOL . PHP_EOL . '++++++++++++++++++++++++++++++++++++++++++++' . PHP_EOL . PHP_EOL . PHP_EOL;
            file_put_contents($this->config['log']['filePath'], $message, FILE_APPEND);
        }
    }
}
