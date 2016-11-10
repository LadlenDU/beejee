<?php

class CommonWidget
{
    public static function headerPanel()
    {
        $s =
            '<nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="container">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="/">Список сообщений</a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">' .
                         (new UserWidget)->htmlLogoutItem(['form' => ['class' => 'navbar-form navbar-right']])
                    . '</div>
                </div>
            </nav>';

        return $s;
    }
}