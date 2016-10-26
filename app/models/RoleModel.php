<?php

#require_once(APP_DIR . 'components/' . DATABASE_CLASS . '.php');

/**
 * Class RoleModel
 *
 * Содержит данные комментариев.
 */
class RoleModel
{
    /**
     * Название таблицы в БД.
     * @var string
     */
    public static $tableName = 'role';

    /*static public function GetAllCities()
    {
        $className = DATABASE_CLASS;
        $db = new $className;
        $res = $db->selectQuery("SELECT * FROM " . self::$tableName);
        return $res;
    }*/
}