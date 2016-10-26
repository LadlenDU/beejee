<?php

#require_once(APP_DIR . 'components/' . DATABASE_CLASS . '.php');

/**
 * Class ImageModel
 *
 * Работа с изображениями.
 */
class ImageModel
{
    /**
     * Название таблицы в БД.
     * @var string
     */
    public static $tableName = 'image';

    /*static public function GetAllCities()
    {
        $className = DATABASE_CLASS;
        $db = new $className;
        $res = $db->selectQuery("SELECT * FROM " . self::$tableName);
        return $res;
    }*/
}