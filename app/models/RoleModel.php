<?php

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
    protected static $tableName = 'role';

    /**
     * Название таблицы в БД - связь с таблицей пользователей.
     * @var string
     */
    protected static $tableConjuctWithUserName = 'user_role';

    public static function getTableName()
    {
        return self::$tableName;
    }

    public static function getConjuctWithUserTableName()
    {
        return self::$tableConjuctWithUserName;
    }

}