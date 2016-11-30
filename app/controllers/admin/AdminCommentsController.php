<?php

class AdminCommentsController extends ControllerController
{
    public function __construct()
    {
        if (!UserComponent::getInstance()->userHasRole('admin'))
        {
            //throw new Exception('У вас нет прав просматривать эту страницу');
            CommonHelper::sendHtmlResponse('У вас нет прав просматривать эту страницу');
        }
    }

    public function actionIndex()
    {
        (new CommentsController(true))->actionIndex();
    }

    public function actionChangeStatus()
    {
        if (CommentModel::setField($_POST['id'], 'status', $_POST['status']))
        {
            //TODO: на самом деле просто установка прошла без ошибок, но это не значит что статус установился
            CommonHelper::sendHtmlResponse('Статус успешно установлен.');
        }
        else
        {
            CommonHelper::sendJsonResponse(false, 'Ошибка назначения статуса.');
        }
    }

    public function actionChangeText()
    {
        if (CommentModel::setField($_POST['id'], 'text', $_POST['text'], true))
        {
            //TODO: на самом деле просто установка прошла без ошибок, но это не значит что статус установился
                CommonHelper::sendHtmlResponse('Текст успешно обновлен.');
        }
        else
        {
            CommonHelper::sendJsonResponse(false, 'Ошибка обновления текста.');
        }
    }
}