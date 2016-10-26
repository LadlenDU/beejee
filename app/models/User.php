<?php

//TODO: что-то здесь не так, но в данном случае одновременно быстрого и оптимального решения не вижу
require_once(APP_DIR . 'components/' . DATABASE_CLASS . '.php');

/**
 * Class UserModel
 *
 * Данные по пользователю.
 */
class User
{
    /**
     * Информация о пользователе.
     * @var
     */
    public $userInfo;

    /**
     * Название таблицы в БД.
     * @var string
     */
    protected static $tableName = 'users';

    /**
     * Конструктор.
     *
     * @param int $id идентификатор пользователя
     */
    public function __construct($id = null)
    {
        if ($id)
        {
            $className = DATABASE_CLASS;
            $db = new $className;
            $this->userInfo = $db->selectQuery('SELECT * FROM ' . self::$tableName . ' WHERE id=%s', $id);
        }
    }

    public static function GetAllUsers()
    {
        $className = DATABASE_CLASS;
        $db = new $className;
        // SELECT users.*, cities.name as city_name FROM users LEFT JOIN cities ON cities.id = users.city_id;
        $usersTb = self::$tableName;
        $citiesTb = City::$tableName;
        $query = "SELECT $usersTb.*, $citiesTb.name as city_name FROM $usersTb "
            . "LEFT JOIN $citiesTb ON $citiesTb.id = $usersTb.city_id";
        $res = $db->selectQuery($query);
        return $res;
    }

    public static function updateUser($id, $name, $value)
    {
        $name = str_replace('`', '', $name);
        $className = DATABASE_CLASS;
        $db = new $className;
        $res = $db->query('UPDATE ' . self::$tableName . " SET `$name`=%s WHERE id=%s", $value, $id);
        return $res;
    }

    public static function createUser($name, $age, $city)
    {
        $className = DATABASE_CLASS;
        $db = new $className;
        $res = $db->query('INSERT INTO ' . self::$tableName . ' SET name=%s, age=%s, city_id=%s', $name, $age, $city);
        return $res;
    }

    /**
     * Проверка данных пользователя.
     *
     * @param array $info список полей пользователя.
     * @return array список ошибок. Пустой если нет ошибок.
     */
    public static function verifyUserInfo($info)
    {
        $errorList = [];

        if (isset($info['name']))
        {
            $nameLenght = mb_strlen($info['name'], DOCUMENT_ENCODING);
            if ($nameLenght < 1)
            {
                $errorList[] = 'пустое имя пользователя';
            }
            elseif ($nameLenght > 30)
            {
                $errorList[] = 'имя пользователя больше 30 символов';
            }
        }

        if (isset($info['age']))
        {
            if (strlen($info['age']) < 1)
            {
                $errorList[] = 'поле возраста пустое';
            }
            else
            {
                $age = (int)$info['age'];
                if ($age < 0)
                {
                    $errorList[] = 'возраст не может быть отрицательным';
                }
                elseif ($age > 255)
                {
                    $errorList[] = 'люди так долго не живут. Если не согласны - обратитесь к администратору';
                }
            }
        }

        return $errorList;
    }

}
