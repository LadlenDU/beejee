<?php

require_once(APP_DIR . 'components/DB.php');

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

    static public function getComments($orderBy = 'created', $orderDir = 'DESC')
    {
        $res = DB::obj()->selectQuery(
            'SELECT * FROM ' . self::$tableName . " WHERE `status` = 'APPROVED' ORDER BY $orderBy $orderDir"
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