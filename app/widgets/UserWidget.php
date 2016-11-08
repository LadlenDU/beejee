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
    public static function htmlLogoutItem($params)
    {
        $s = '<button type="submit" ';
        if (!empty($params['class']))
        {
            $s .= ' class="' . htmlspecialchars($params['class'], ENT_QUOTES, ConfigHelper::getConfig()['globalEncoding']) . '" ';
        }
        $s .= '>' . CommonHelper::_h('Выйти') . '</button>';
        return $s;
    }
}