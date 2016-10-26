<?php

require_once(APP_DIR . 'controllers/Controller.php');
#require_once(APP_DIR . 'models/User.php');
require_once(APP_DIR . 'models/Comment.php');

#require_once(APP_DIR . 'helpers/prepareJSON.php');

class CommentsController extends Controller
{
    public function actionIndex()
    {
        #$model = User::GetAllUsers();
        #$cities = City::GetAllCities();
        #$this->render(APP_DIR . 'views/Comments.php', ['model' => $model->rows, 'cities' => $cities->rows]);

        $orderBy = (isset($_REQUEST['comments']['order']['by']) && in_array(
                Comment::getValidOrderFields(),
                $_REQUEST['comments']['order']['by'],
                true
            )) ? $_REQUEST['comments']['order']['by'] : 'created';

        $orderDir = (isset($_REQUEST['comments']['order']['dir']) && DB::obj()->ifValidOrderDirection(
                $_REQUEST['comments']['order']['dir']
            )) ? $_REQUEST['comments']['order']['dir'] : 'DESC';

        $comments = Comment::getComments($orderBy, $orderDir);
        $orderFields = Comment::getValidOrderFields();
        $orderLabels = Comment::getLabels();
        $orderTypes = [];
        foreach ($orderFields as $ot)
        {
            $orderTypes[] = ['id' => $ot, 'name' => $orderLabels[$ot]];
        }

        $fieldMaxLength = [];
        $fieldMaxLength['name'] = DB::obj()->getCharacterMaximumLength(Comment::$tableName, 'username');
        $fieldMaxLength['email'] = DB::obj()->getCharacterMaximumLength(Comment::$tableName, 'email');
        $fieldMaxLength['text'] = DB::obj()->getCharacterMaximumLength(Comment::$tableName, 'text');

        $this->render(
            APP_DIR . 'views/Comments.php',
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