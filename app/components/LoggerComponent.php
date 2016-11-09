<?php

class LoggerComponent extends SingletonHelper
{
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
