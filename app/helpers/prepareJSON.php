<?php

class prepareJSON
{
    /**
     * Конвертирует все строки массива в кодировку UTF-8.
     *
     * @param array $arr
     * @return array
     */
    public static function jsonEncode($arr)
    {
        array_walk_recursive($arr, function(&$item) {
           if(is_string($item))
           {
               $item = mb_convert_encoding($item, 'UTF-8', DOCUMENT_ENCODING);
           }
        });

        return json_encode($arr);
    }
}

