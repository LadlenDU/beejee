<?php

class AdminDefaultController extends ControllerController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function actionIndex()
    {
        $this->render('index');
    }

}