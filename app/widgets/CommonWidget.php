<?php

class CommonWidget
{
    public static function headerPanel()
    {
        $s =
            '<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="container">
                    <div class="navbar-header">';
        if (UserComponent::getInstance()->getUserId())
        {
            $s .=

                '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#w0-collapse">
                    <span class="sr-only">Переключить навигацию</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>';
        }
        $s .= '<a class="navbar-brand" href="/">Список сообщений</a>
                    </div>
                    <div class="navbar-collapse collapse" aria-expanded="true" id="w0-collapse">' .
            (new UserWidget)->htmlLogoutItem(
                [
                    'form' => ['class' => 'navbar-right nav', 'role' => 'form'],
                    'button' => ['class' => 'btn navbar-btn', 'type' => 'submit']
                ]
            )
            . '      </div>
                </div>
            </nav>';

        return $s;
    }
}