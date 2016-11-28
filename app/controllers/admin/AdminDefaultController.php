<?php

class AdminDefaultController extends ControllerController
{
    public function _construct()
    {
        if (!UserComponent::getInstance()->userHasRole('admin'))
        {
            throw new Exception('У вас нет прав просматривать эту страницу');
        }
    }

    public function actionIndex()
    {
        (new CommentsController(true))->actionIndex();
    }
}