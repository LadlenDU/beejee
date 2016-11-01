<?php

class DefaultController extends ControllerController
{
    public function __construct($config)
    {
        parent::__construct($config);
    }

    public function actionIndex()
    {
        $this->render('index');
    }

    public function action404()
    {
        header('HTTP/1.0 404 Not Found');
        $this->render('404');
    }
}