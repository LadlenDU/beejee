<?php

/**
 * Class CommentModel
 *
 * Содержит данные комментариев.
 */
class CommentModel
{
    /**
     * Название таблицы в БД.
     * @var string
     */
    protected static $tableName = 'comment';

    public static function getTableName()
    {
        return self::$tableName;
    }

    public static function getComments($orderBy = 'created', $orderDir = 'DESC')
    {
        $res = DbHelper::obj()->selectQuery(
            'SELECT * FROM ' . self::$tableName . ' AS com '
            . ' LEFT JOIN ' . ImageModel::getTableName() . ' AS img ON com.image_id=img.id '
            . " WHERE `status` = 'APPROVED' ORDER BY $orderBy $orderDir"
        );

        return $res;
    }

    /**
     * Поля по которым сортируем.
     *
     * @return array
     */
    public static function getValidOrderFields()
    {
        return ['created', 'name', 'email'];
    }

    public static function getLabels()
    {
        return [
            'created' => _('Дата создания'),
            'name' => _('Имя'),
            'email' => _('Email'),
        ];
    }
}