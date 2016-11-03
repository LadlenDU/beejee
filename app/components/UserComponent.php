<?php

/**
 * Class UserComponent
 *
 * Управление пользователями.
 */
class UserComponent
{
    /**
     * Логин пользователя.
     *
     * @param string $username имя пользователя
     * @param string $password пароль
     */
    public function logIn($username, $password)
    {
        $result = DbHelper::obj()->selectQuery(
            'SELECT id FROM ' . UserModel::getTableName() . ' WHERE login=%s AND password_hash=PASSWORD(%s)',
            $username,
            $password
        );

        if (count($result->rows) == 1)
        {
            $result->rows[0];
        }
    }

    public function logOut()
    {

    }
}