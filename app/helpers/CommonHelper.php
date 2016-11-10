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
        return htmlspecialchars(_($str), ENT_QUOTES, ConfigHelper::getInstance()->getConfig()['globalEncoding']);
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
            $s .= ' ' . htmlspecialchars(
                    $pName,
                    ENT_QUOTES,
                    ConfigHelper::getInstance()->getConfig()['globalEncoding']
                ) . '="' .
                htmlspecialchars($pVal, ENT_QUOTES, ConfigHelper::getInstance()->getConfig()['globalEncoding']) . '" ';
        }


        return $s;
    }

    /**
     * @param string $part часть
     * @param bool|true $forHtml подготовить ли заголовок для вывода в html код
     * @return string
     */
    public static function createTitle($part = '', $forHtml = true)
    {
        $conf = ConfigHelper::getInstance()->getConfig();
        assert(isset($conf['site']['name']));

        $leftPart = _(ConfigHelper::getInstance()->getConfig()['site']['name']);
        $rightPart = $part ? _($part) : '';

        if ($forHtml)
        {
            $leftPart = htmlspecialchars($leftPart, ENT_QUOTES, $conf['globalEncoding']);
            $rightPart = htmlspecialchars($rightPart, ENT_QUOTES, $conf['globalEncoding']);
        }

        return $rightPart ? ($leftPart . ' - ' . $rightPart) : $leftPart;
    }

    /**
     * Создать тег для добавления файла CSS в хеадер.
     *
     * @param string $file путь к файлу
     */
    public static function createCssLink($file)
    {
        $lnk = '<link rel="stylesheet" href="'
            . htmlspecialchars($file, ENT_QUOTES, ConfigHelper::getInstance()->getConfig()['globalEncoding'])
            . '">' . "\n";

        return $lnk;
    }
}