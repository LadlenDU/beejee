<?php

class FormWidget
{
    public static function startForm($params)
    {
        $params['method'] = isset($params['method']) ? $params['method'] : 'POST';

        $s = '<form ' . CommonHelper::getHtmlTagParams($params) . ">\n"
            . '<input type="hidden" name="' . CsrfHelper::getInstance()->getCsrfTokenName() . '" '
            . 'value="' . CsrfHelper::getInstance()->getCsrfToken() . '">' . "\n";

        return $s;
    }

    public static function endForm()
    {
        echo "</form>\n";
    }
}
