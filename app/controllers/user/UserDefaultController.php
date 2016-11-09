<?php

class UserDefaultController extends ControllerController
{
    public function actionIndex()
    {
        (new DefaultController)->action404();
    }

    public function actionLogin()
    {
        $this->render('login');
    }

}