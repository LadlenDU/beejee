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

    public static function getComments($orderBy = 'created', $orderDir = 'DESC', $status = 'APPROVED')
    {
        $sql = 'SELECT * FROM `' . self::$tableName . '` AS `com` ';
        //. ' LEFT JOIN ' . ImageModel::getTableName() . ' AS img ON com.image_id=img.id '
        //. " WHERE `status` = 'APPROVED' ORDER BY $orderBy $orderDir"
        if ($status)
        {
            $sql .= " WHERE `status` = '" . $status . "' ";
        }
        $sql .= " ORDER BY `$orderBy` $orderDir";

        $res = DbHelper::obj()->selectQueryA($sql);

        return $res;
    }

    public static function setNewComment($data)
    {
        $sql = 'INSERT INTO `' . self::$tableName . '` SET `username` = %s, `email` = %s, `text` = %s, `image_name` = %s';
        return DbHelper::obj()->query($sql, $data['username'], $data['email'], $data['text'], $data['image_name']);
    }

    public static function setField($id, $field, $value)
    {
        $sql = 'UPDATE `' . self::$tableName . '` SET `' . $field . '` = %s WHERE `id` = %s';
        return DbHelper::obj()->query($sql, $value, $id);
    }

    /**
     * Поля по которым сортируем.
     *
     * @return array
     */
    public static function getValidOrderFields()
    {
        return ['created', 'username', 'email'];
    }

    public static function getLabels()
    {
        return [
            'created' => _('Дата создания'),
            'username' => _('Имя'),
            'email' => _('Email'),
        ];
    }
}