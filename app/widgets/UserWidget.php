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
            $params['form'] = isset($params['form']) ? $params['form'] : [];
            $params['button'] = isset($params['button']) ? $params['button'] : [];

            $params['form']['action'] = '/user/logout';
            $params['form']['method'] = 'POST';

            $s .= FormWidget::startForm($params['form'])
                . '<input type="hidden" name="logout" value="1">'
                . '<p class="navbar-text">'
                . CommonHelper::_h('Ваш логин:') . ' ' . UserComponent::getInstance()->getUserInfo()->login
                . '</p><button type="submit" '
                . CommonHelper::getHtmlTagParams($params['button'])
                . '>' . CommonHelper::_h('Выйти') . '</button>'
                . FormWidget::endForm();
        }

        return $s;
    }
}