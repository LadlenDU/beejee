<?php

class FormWidget
{
    public static function startForm($params)
    {
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
