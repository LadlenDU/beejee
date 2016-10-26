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

    static public function getComments()
    {
        $orderBy = (isset($_REQUEST['comments']['order']['by']) && in_array(
                self::getValidOrderFields(),
                $_REQUEST['comments']['order']['by'],
                true
            )) ? $_REQUEST['comments']['order']['by'] : 'created';

        $orderDir = (isset($_REQUEST['comments']['order']['dir']) && DB::obj()->ifValidOrderDirection(
                $_REQUEST['comments']['order']['dir']
            )) ? $_REQUEST['comments']['order']['dir'] : 'DESC';

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
    static private function getValidOrderFields()
    {
        return array('created', 'name', 'email');
    }
}