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
}