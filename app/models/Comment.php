<?php

require_once(APP_DIR . 'components/' . DATABASE_CLASS . '.php');

/**
 * Class Comment
 *
 * Содержит данные комментариев.
 */
class Comment
{
    /**
     * Название таблицы в БД.
     * @var string
     */
    public static $tableName = 'comment';

    /*static public function GetAllCities()
    {
        $className = DATABASE_CLASS;
        $db = new $className;
        $res = $db->selectQuery("SELECT * FROM " . self::$tableName);
        return $res;
    }*/

    static public function GetComments()
    {
        $className = DATABASE_CLASS;
        $db = new $className;
        $res = $db->selectQuery('SELECT * FROM ' . self::$tableName . ' LIMIT');
        return $res;
    }
}