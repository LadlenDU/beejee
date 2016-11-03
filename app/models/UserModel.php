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

    static public function getRolesById($id)
    {
        $result = DbHelper::obj()->selectQuery(
            'SELECT `role`.`name` FROM `' . RoleModel::getConjuctWithUserTableName() . '` AS `user_role` '
            . 'LEFT JOIN ' . self::$tableName . ' AS `user` ON `user`.`id` = `user_role`.`user_id` '
            . 'LEFT JOIN `' . RoleModel::getTableName() . '` AS `role` ON `role`.`id` = `user_role`.`role_id` '
            . 'WHERE `user`.`id` = %d',
            $id
        );
    }
}
