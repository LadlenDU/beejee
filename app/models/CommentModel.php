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
    public static $tableName = 'comment';

    static public function getComments($orderBy = 'created', $orderDir = 'DESC')
    {
        $res = DbHelper::obj()->selectQuery(
            'SELECT * FROM ' . self::$tableName . ' AS com '
            . ' LEFT JOIN ' . ImageModel::$tableName . ' AS img ON com.image_id=img.id '
            . " WHERE `status` = 'APPROVED' ORDER BY $orderBy $orderDir"
        );

        return $res;
    }

    /**
     * Поля по которым сортируем.
     *
     * @return array
     */
    static public function getValidOrderFields()
    {
        return ['created', 'name', 'email'];
    }

    static public function getLabels()
    {
        return [
            'created' => _('Дата создания'),
            'name' => _('Имя'),
            'email' => _('Email'),
        ];
    }
}