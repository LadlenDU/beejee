<?php

/**
 * Class UserComponent
 *
 * Управление пользователями.
 */
class UserComponent
{
    private static $instance;  // экземпляр объекта

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance()
    {
        if (empty(self::$instance))
        {
            session_start();
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Логин пользователя.
     *
     * @param string $username имя пользователя
     * @param string $password пароль
     * @return bool
     */
    public function logIn($username, $password)
    {
        $success = false;

        $result = DbHelper::obj()->selectQuery(
            'SELECT id FROM ' . UserModel::getTableName() . ' WHERE login=%s AND password_hash=PASSWORD(%s)',
            $username,
            $password
        );

        if (count($result->rows) == 1)
        {
            $_SESSION['user'] = [];
            $_SESSION['user']['logged'] = true;
            $_SESSION['user']['id'] = $result->rows[0]['id'];
            $success = true;
        }

        return $success;
    }

    /**
     * Разлогирование пользователя.
     */
    public function logOut()
    {
        assert($_SESSION['user']);
        assert($_SESSION['user']['logged']);
        assert($_SESSION['user']['id']);

        unset($_SESSION['user']['logged']);
    }

    /**
     * Выдать роли пользователя. Если $id не назначен, подразумевается залогиненый пользователь.
     *
     * @param int|bool $id id пользователя
     * @return array|bool возвращает false если пользователь не найден
     */
    public function getUserRoles($id = false)
    {
        $roles = false;

        if (!$id)
        {
            if (isset($_SESSION['user']) && isset($_SESSION['user']['logged']))
            {
                assert($_SESSION['user']['id']);
                $id = $_SESSION['user']['id'];
            }
        }

        if ($id)
        {
            $roles = UserModel::getRolesById($id);
        }

        return $roles;
    }
}