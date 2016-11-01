<?php

/**
 * @param string $className
  */
function __autoload($className)
{
    $matches = array();
    $matchesCount = preg_match_all("/[A-Z]+[a-z0-9_]*/", $className, $matches);
    if ($matchesCount > 1)
    {
        $firstDirName = APP_DIR . strtolower(end($matches[0])) . 's';
        $trimmedMatches = $matches[0];
        unset($trimmedMatches[count($trimmedMatches) - 1], $trimmedMatches[count($trimmedMatches) - 1]);
        array_walk(
            $trimmedMatches,
            function (&$str)
            {
                $str = strtolower($str);
            }
        );
        $subPath = implode('/', $trimmedMatches);
        $dirName = rtrim("$firstDirName/$subPath", '/');
        if (is_dir($dirName))
        {
            $fileName = "$dirName/$className.php";
            if (is_readable($fileName))
            {
                require_once($fileName);
            }
        }
    }
}
