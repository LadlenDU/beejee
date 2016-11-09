<?php

class LoggerComponent extends SingletonHelper
{
    public function log($message)
    {
        assert(!empty(ConfigHelper::getInstance()->getConfig()['log']['filePath']));

        $fPath = ConfigHelper::getInstance()->getConfig()['log']['filePath'];
        if (is_writable($fPath))
        {
            $message .= PHP_EOL . PHP_EOL . '++++++++++++++++++++++++++++++++++++++++++++' . PHP_EOL . PHP_EOL . PHP_EOL;
            file_put_contents($fPath, $message, FILE_APPEND);
        }
    }
}
