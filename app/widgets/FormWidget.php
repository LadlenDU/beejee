<?php

class FormWidget
{
    public static function startForm($)
    {
        echo '<form type="submit" class="btn btn-success">';
    }

    public static function endForm()
    {
        echo '</form>';
    }
}
