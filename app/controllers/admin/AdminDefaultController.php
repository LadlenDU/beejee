<?php

class AdminDefaultController extends ControllerController
{
    public function __construct($config)
    {
        parent::__construct($config);
    }

    public function actionIndex()
    {
        $this->render('index');
    }

}