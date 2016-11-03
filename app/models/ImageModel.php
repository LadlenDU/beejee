<?php

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
    protected static $tableName = 'image';

    public static function getTableName()
    {
        return self::$tableName;
    }
}