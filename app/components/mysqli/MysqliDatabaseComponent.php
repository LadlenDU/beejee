<?php

/**
 * Class MySqliDatabaseComponent
 *
 * Работа с БД mySqli.
 */
class MysqlidatabaseComponent implements DatabaseInterface
{
    protected static $mySqlLink;

    protected $config;

    public function __construct()
    {
        $this->config = ConfigComponent::getConfig();
        $this->mysqli_prepare();
    }

    protected function mysqli_prepare()
    {
        if (is_null(self::$mySqlLink))
        {
            self::$mySqlLink = mysqli_connect(
                $this->config['database']['connection']['host'],
                $this->config['database']['connection']['user'],
                $this->config['database']['connection']['password'],
                $this->config['database']['connection']['databaseName']
            );
            if (mysqli_connect_errno())
            {
                throw new Exception('Не установлено соединение с базой данных : ' . mysqli_error(self::$mySqlLink));
            }
            $dbSelected = mysqli_select_db(self::$mySqlLink, $this->config['database']['connection']['databaseName']);
            if (!$dbSelected)
            {
                throw new Exception('Не могу выбрать базу данных : ' . mysqli_error(self::$mySqlLink));
            }
            mysqli_set_charset(self::$mySqlLink, DOCUMENT_ENCODING);
        }
    }

    protected function replaceAndClean($sql)
    {
        $args = func_get_args();
        if (count($args) == 1)
        {
            return $args[0];
        }
        $query = array_shift($args);
        return vsprintf($query, array_map(array($this, 'escape_string'), $args));
    }

    /**
     * @inheritdoc
     */
    public function selectQuery($sql)
    {
        $ret = new stdClass();

        $query = call_user_func_array(array($this, 'replaceAndClean'), func_get_args());
        if (!$result = mysqli_query(self::$mySqlLink, $query))
        {
            throw new Exception('Ошибка mysql : ' . mysqli_error(self::$mySqlLink));
        }

        $allRows = array();
        while ($row = mysqli_fetch_object($result))
        {
            $allRows[] = $row;
        }
        $ret->rows = $allRows;

        mysqli_free_result($result);

        return $ret;
    }

    /**
     * @inheritdoc
     * @return bool|mysqli_result
     */
    public function query($sql)
    {
        $query = call_user_func_array(array($this, 'replaceAndClean'), func_get_args());
        $ret = mysqli_query(self::$mySqlLink, $query);

        if (mysqli_errno(self::$mySqlLink))
        {
            $ret = false;
        }

        return $ret;
    }

    public function escape_string($string, $quotes = true)
    {
        $ret = mysqli_real_escape_string(self::$mySqlLink, $string);
        $ret = $quotes ? "'$ret'" : $ret;
        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function ifValidOrderDirection($order)
    {
        return in_array(strtoupper($order), array('DESC', 'ASC'), true);
    }

    /**
     * @inheritdoc
     */
    public function getCharacterMaximumLength($table, $column)
    {
        $sql = 'SELECT character_maximum_length FROM information_schema.columns'
            . ' WHERE  table_schema = Database() '
            . ' AND '
            . ' table_name = ' . $this->escape_string($table)
            . ' AND '
            . ' column_name = ' . $this->escape_string($column);

        return $this->selectQuery($sql)->rows[0]->character_maximum_length;
    }

}
