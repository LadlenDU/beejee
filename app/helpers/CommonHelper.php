<?php

/**
 * Class CommonHelper
 *
 * Общие вспомогательные функции.
 */
class CommonHelper
{
    /**
     * Проверка является ли запрос AJAX запросом.
     *
     * @return bool
     */
    public static function ifAjax()
    {
        return !empty($_REQUEST['ajax']);
    }

    /**
     * Перенаправление на другую локацию этой же страницы.
     *
     * @param string $path путь и ([параметры][якорь]) после названия хоста (порта)
     */
    public static function redirect($path = '')
    {
        $host = $_SERVER['HTTP_HOST'];
        header("Location: http://$host/$path");
        exit;
    }

    /**
     * Начинает сессию если ещё не начата.
     */
    public static function startSession()
    {
        if (session_id() == '' || !isset($_SESSION))
        {
            session_start();
        }
    }

    /**
     * Перевод строки + конвертация в специальные html символы.
     *
     * @param string $str
     * @return string
     */
    public static function _h($str)
    {
        return htmlspecialchars(_($str), ENT_QUOTES, ConfigHelper::getConfig()['globalEncoding']);
    }

    /**
     * Возвращает строку параметров для вставки в html тег.
     *
     * @param array $params список [параметр => значение[, ...]]
     * @return string
     */
    public static function getHtmlTagParams($params = [])
    {
        $s = '';

        $params = (array)$params;
        foreach ($params as $pName => $pVal)
        {
            $s .= ' ' . htmlspecialchars($pName, ENT_QUOTES, ConfigHelper::getConfig()['globalEncoding']) . '="' .
                htmlspecialchars($pVal, ENT_QUOTES, ConfigHelper::getConfig()['globalEncoding']) . '" ';
        }


        return $s;
    }
}