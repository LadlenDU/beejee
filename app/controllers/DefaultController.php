<?php

class DefaultController extends ControllerController
{
    public function actionIndex()
    {
        #CommonHelper::redirect('comments');
        $this->title = CommonHelper::createTitle('главная страница');
        $this->render('index');
    }

    public function action404()
    {
        $this->title = CommonHelper::createTitle('страница не найдена');
        header('HTTP/1.0 404 Not Found');
        $this->render('404');
    }
}