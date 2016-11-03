<?php

/**
 * Class UserModel
 */
class UserModel
{
    /**
     * Название таблицы в БД.
     * @var string
     */
    protected static $tableName = 'user';

    public static function getTableName()
    {
        return self::$tableName;
    }
}
