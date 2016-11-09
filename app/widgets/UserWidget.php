<?php

/**
 * Class UserWidget
 *
 * Элементы, связанные с работой с пользователями.
 */
class UserWidget
{
    /**
     * Возвращает строку для разлогинивания пользователя (также краткую информацию о пользователе).
     *
     * @param array $params массив параметров
     * @return string html строка для разлогинивания
     */
    public static function htmlLogoutItem($params = [])
    {
        $s = '<div class="user_logout"><div class="username">' .
            CommonHelper::_h('Ваш логин:') . ' ' . UserComponent::getUserInfo()->login . '</div><button type="submit" ';
        if ($params)
        {
            $s .= CommonHelper::getHtmlTagParams($params);
        }
        $s .= '>' . CommonHelper::_h('Выйти') . '</button></div></div>';
        return $s;
    }
}