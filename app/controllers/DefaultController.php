<?php

class DefaultController extends ControllerController
{
    public function actionIndex()
    {
        #CommonHelper::redirect('comments');
        $this->render('index');
    }

    public function action404()
    {
        header('HTTP/1.0 404 Not Found');
        $this->render('404');
    }
}