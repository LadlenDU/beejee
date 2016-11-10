<?php

/**
 * Class UserComponent
 *
 * Управление пользователями.
 */
class UserComponent extends SingletonHelper
{
    public static function getInstance()
    {
        CommonHelper::startSession();
        return parent::getInstance();
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
            $_SESSION['user']['logged'] = true;
            $_SESSION['user']['id'] = $result->rows[0]->id;
            $success = true;
        }

        return $success;
    }

    /**
     * Разлогирование пользователя.
     */
    public function logOut()
    {
        #assert($_SESSION['user']['logged']);
        #assert($_SESSION['user']['id']);

        unset($_SESSION['user']['logged']);
        unset($_SESSION['user']['id']);
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

        if ($nId = $this->getUserId($id))
        {
            $roles = UserModel::getRolesById($nId);
        }

        return $roles;
    }

    public function getUserInfo($id = false)
    {
        $info = false;

        if ($nId = $this->getUserId($id))
        {
            $info = UserModel::getUserInfo($nId);
        }

        return $info;
    }

    /**
     * Возвращает сессионный id залогиненого пользователя если id не задан.
     *
     * @param int $id
     * @return mixed
     */
    public function getUserId($id = false)
    {
        if (!$id)
        {
            if (isset($_SESSION['user']['logged']))
            {
                assert($_SESSION['user']['id']);
                $id = $_SESSION['user']['id'];
            }
        }

        return $id;
    }
}