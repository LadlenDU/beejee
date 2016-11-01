<?php

class CommentsController extends ControllerController
{
    public function __construct($config)
    {
        parent::__construct($config);
        DbHelper::setConfig($config);
    }

    public function actionPreview()
    {
        $this->renderPartial(
            '_comment',
            ['item' => []]
        );
        exit;
    }

    public function actionIndex()
    {
        $orderBy = (isset($_REQUEST['comments']['order']['by']) && in_array(
                CommentModel::getValidOrderFields(),
                $_REQUEST['comments']['order']['by'],
                true
            )) ? $_REQUEST['comments']['order']['by'] : 'created';

        $orderDir = (isset($_REQUEST['comments']['order']['dir']) && DbHelper::obj()->ifValidOrderDirection(
                $_REQUEST['comments']['order']['dir']
            )) ? $_REQUEST['comments']['order']['dir'] : 'DESC';

        $comments = CommentModel::getComments($orderBy, $orderDir);
        $orderFields = CommentModel::getValidOrderFields();
        $orderLabels = CommentModel::getLabels();
        $orderTypes = [];
        foreach ($orderFields as $ot)
        {
            $orderTypes[] = ['id' => $ot, 'name' => $orderLabels[$ot]];
        }

        $fieldMaxLength = [];
        $fieldMaxLength['name'] = DbHelper::obj()->getCharacterMaximumLength(CommentModel::$tableName, 'username');
        $fieldMaxLength['email'] = DbHelper::obj()->getCharacterMaximumLength(CommentModel::$tableName, 'email');
        $fieldMaxLength['text'] = DbHelper::obj()->getCharacterMaximumLength(CommentModel::$tableName, 'text');

        $this->render(
            'index',
            ['comments' => $comments->rows, 'orderTypes' => $orderTypes, 'fieldMaxLength' => $fieldMaxLength]
        );
    }

    public function actionUpdate()
    {
        $ret = ['success' => false];

        $_REQUEST['value'] = trim($_REQUEST['value']);

        $model = new User();
        $win1251Value = mb_convert_encoding($_REQUEST['value'], DOCUMENT_ENCODING, 'UTF-8');

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

    public function actionCreateNewUser()
    {
        $ret = ['success' => false];

        $_REQUEST['name'] = trim($_REQUEST['name']);

        $model = new User();
        $win1251Name = mb_convert_encoding($_REQUEST['name'], DOCUMENT_ENCODING, 'UTF-8');

        if ($errors = $model->verifyUserInfo(['name' => $win1251Name, 'age' => $_REQUEST['age']]))
        {
            $ret = ['success' => false, 'messages' => $errors];
        }
        elseif ($model->createUser($win1251Name, $_REQUEST['age'], $_REQUEST['city']))
        {
            $ret['success'] = true;
        }

        echo prepareJSON::jsonEncode($ret);
        exit;
    }
}