<?php

class AdminWidget extends ControllerController
{
    public function actionIndex()
    {
        echo 'admin widget default behaviour';
    }

    public function renderLogoutItem()
    {
        echo '<button type="submit" class="btn btn-success">Выйти</button>';
    }
}