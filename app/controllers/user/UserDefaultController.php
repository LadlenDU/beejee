<?php

class UserDefaultController extends ControllerController
{
    public function actionIndex()
    {
        (new DefaultController)->action404();
    }

    public function actionLogin()
    {
        $this->title = CommonHelper::createTitle('логин пользователя');

        $wrong_login = false;
        if (isset($_POST['login']))
        {
            if (UserComponent::getInstance()->logIn($_POST['login'], $_POST['password']))
            {
                CommonHelper::redirect('comments');
            }
            else
            {
                $wrong_login = true;
            }
        }

        $this->render('login', ['wrong_login' => $wrong_login]);
    }

    public function actionLogout()
    {
        if (!empty($_POST['logout']))
        {
            UserComponent::getInstance()->logOut();
            CommonHelper::redirect();
        }
    }
}