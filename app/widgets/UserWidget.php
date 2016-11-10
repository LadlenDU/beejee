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
        $s = '';

        if (UserComponent::getInstance()->getUserId())
        {
            $s .= '<form '
                . (isset($params['form']) ? CommonHelper::getHtmlTagParams($params['form']) : '') . '>'
                . '<p class="navbar-text">'
                . CommonHelper::_h('Ваш логин:') . ' ' . UserComponent::getInstance()->getUserInfo()->login
                . '</p><button type="submit" '
                . (isset($params['button']) ? CommonHelper::getHtmlTagParams($params['button']) : '')
                . '>' . CommonHelper::_h('Выйти') . '</button></form>';
        }

        return $s;
    }
}