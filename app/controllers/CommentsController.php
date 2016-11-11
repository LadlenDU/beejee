<?php

class CommentsController extends ControllerController
{
    public function actionPreview()
    {
        $this->renderPartial(
            '_comment',
            ['item' => []]
        );

        if (!empty($_FILES['image']['tmp_name']))
        {
            if ($_FILES['image']['size'] > ConfigHelper::getInstance(
                )->getConfig['site']['comments']['creation_settings']['max_file_size']
            )
            {
                CommonHelper::sendJsonResponse(false, ['message' => 'Вы пытались загрузить слишком большой файл.']);
            }

            $destFile = ConfigHelper::getInstance()->getConfig['appDir'] . '/runtime/temp_uploaded_files/'
                . basename($_FILES['image']['tmp_name']);
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $destFile))
            {
                CommonHelper::startSession();
                $_SESSION['preview_image'] = $destFile;
            }
            else
            {
                CommonHelper::sendJsonResponse(false, ['message' => 'Ошибка загрузки файла.']);
            }

        }

        exit;
    }

    public function actionIndex()
    {
        $this->title = CommonHelper::createTitle('список комментариев');
        $this->scripts['css'] .= CommonHelper::createCssLink('/css/comments.css');
        $this->scripts['js'] .= CommonHelper::createJsLink('/js/comments.js');

        $orderBy = (isset($_POST['comments']['order']['by']) && in_array(
                CommentModel::getValidOrderFields(),
                $_POST['comments']['order']['by'],
                true
            )) ? $_POST['comments']['order']['by'] : 'created';

        $orderDir = (isset($_POST['comments']['order']['dir']) && DbHelper::obj()->ifValidOrderDirection(
                $_POST['comments']['order']['dir']
            )) ? $_POST['comments']['order']['dir'] : 'DESC';

        $comments = CommentModel::getComments($orderBy, $orderDir);
        $orderFields = CommentModel::getValidOrderFields();
        $orderLabels = CommentModel::getLabels();
        $orderTypes = [];
        foreach ($orderFields as $ot)
        {
            $orderTypes[] = ['id' => $ot, 'name' => $orderLabels[$ot]];
        }

        $fieldMaxLength = [];
        $fieldMaxLength['name'] = DbHelper::obj()->getCharacterMaximumLength(CommentModel::getTableName(), 'username');
        $fieldMaxLength['email'] = DbHelper::obj()->getCharacterMaximumLength(CommentModel::getTableName(), 'email');
        $fieldMaxLength['text'] = DbHelper::obj()->getCharacterMaximumLength(CommentModel::getTableName(), 'text');

        $imageParams = ConfigHelper::getInstance()->getConfig()['site']['comments']['creation_settings'];

        $this->render(
            'index',
            [
                'comments' => $comments->rows,
                'orderTypes' => $orderTypes,
                'fieldMaxLength' => $fieldMaxLength,
                'imageParams' => $imageParams
            ]
        );
    }

    public function actionUpdate()
    {
        $ret = ['success' => false];

        $_REQUEST['value'] = trim($_REQUEST['value']);

        $model = new User();
        $win1251Value = mb_convert_encoding(
            $_REQUEST['value'],
            ConfigHelper::getInstance()->getConfig()['globalEncoding'],
            'UTF-8'
        );

        if ($errors = $model->verifyUserInfo([$_REQUEST['name'] => $win1251Value]))
        {
            $ret = ['success' => false, 'messages' => $errors];
        }
        else
        {
            if ($model->updateUser($_REQUEST['id'], $_REQUEST['name'], $win1251Value))
            {
                $ret = ['success' => true, 'value' => $_REQUEST['value']];
            }
        }

        echo prepareJSON::jsonEncode($ret);
        exit;
    }

}