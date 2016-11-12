<?php

class CommentsController extends ControllerController
{
    /**
     * Валидация данных о комментарии (рассчитано на использование при $_POST запросе).
     *
     * @param array $data подготовленная информация о комментарии (к примеру $_POST запроса)
     */
    protected function validateCommentIncomingData($data)
    {
        $validationData = $data;

        if (!empty($_FILES['image']['tmp_name']))
        {
            if ($_FILES['image']['size'] > ConfigHelper::getInstance()->getConfig(
                )['site']['comments']['creation_settings']['max_file_size']
            )
            {
                CommonHelper::sendJsonResponse(false, ['message' => 'Вы пытались загрузить слишком большой файл.']);
            }

            $validationData['image'] = $_FILES['image']['tmp_name'];
        }

        if (!$this->commentDataValidation($validationData))
        {
            CommonHelper::sendJsonResponse(false, ['message' => 'Не пройдена валидация.']);
        }
    }

    protected function mergeTableFieldsAndIncomingData($data)
    {
        $fields = [];
        $sqlFields = DbHelper::obj()->getFieldsName(CommentModel::getTableName());
        foreach ($sqlFields as $fld)
        {
            $fields[$fld->COLUMN_NAME] = '';
        }
        $fields = array_merge($fields, $data);

        return $fields;
    }

    public function actionPreview()
    {
        $data = $_POST;
        #$data['image'] = [];

        $data['username'] = trim($data['username']);
        $data['email'] = trim($data['email']);

        $this->validateCommentIncomingData($data);
        $fields = $this->mergeTableFieldsAndIncomingData($data);

        $fields['images_data'] = [];

        if (!empty($_FILES['image']['tmp_name']))
        {
            /*$destFile = ConfigHelper::getInstance()->getConfig()['appDir'] . '/user_data/images_temp/'
                . basename($_FILES['image']['tmp_name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destFile))
            {
                CommonHelper::startSession();
                $_SESSION['preview_image'] = $destFile;
            }
            else
            {
                CommonHelper::sendJsonResponse(false, ['message' => 'Ошибка загрузки файла.']);
            }*/

            if (is_uploaded_file($_FILES['image']['tmp_name']))
            {
                if (($images = ImageHelper::reduceImageToMaxDimensions($_FILES['image']['tmp_name'], true, true))
                    && (!empty($images['new']) && !empty($images['new_thumb']))
                )
                {
                    $images['new']['src'] = '/comments/show_temp_image?name=' . $images['new']['name'];
                    $images['new_thumb']['src'] = '/comments/show_temp_image?name=' . $images['new_thumb']['name'];

                    $fields['images_data']['image'] = $images['new'];
                    $fields['images_data']['image_thumb'] = $images['new_thumb'];
                }
                else
                {
                    LoggerComponent::getInstance()->log('Ошибка сохранения загруженного файла.');
                }
            }
            else
            {
                CommonHelper::sendJsonResponse(false, ['message' => 'Ошибка загрузки файла.']);
            }
        }

        $html = $this->renderPartial(
            '_comment',
            ['item' => $fields]
        );

        //CommonHelper::sendJsonResponse(true, $html);
        CommonHelper::sendTextResponse($html);
    }

    protected function commentDataValidation($data)
    {
        $ret = false;

        $encoding = ConfigHelper::getInstance()->getConfig()['globalEncoding'];

        $lName = mb_strlen($data['username'], $encoding);
        $lEmail = mb_strlen($data['email'], $encoding);
        $lText = mb_strlen($data['text'], $encoding);
        if (($lName >= 1 && $lName <= DbHelper::obj()->getCharacterMaximumLength(
                    CommentModel::getTableName(),
                    'username'
                ))
            && ($lEmail >= 5 && $lEmail <= DbHelper::obj()->getCharacterMaximumLength(
                    CommentModel::getTableName(),
                    'email'
                ))
            && ($lText >= 5 && $lText <= DbHelper::obj()->getCharacterMaximumLength(
                    CommentModel::getTableName(),
                    'text'
                ))
            && filter_var($data['email'], FILTER_VALIDATE_EMAIL)
            && (empty($data['image']) || CommonHelper::validateCommentImage($data['image']))
        )
        {
            $ret = true;
        }

        return $ret;
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
        $fieldMaxLength['username'] = DbHelper::obj()->getCharacterMaximumLength(
            CommentModel::getTableName(),
            'username'
        );
        $fieldMaxLength['email'] = DbHelper::obj()->getCharacterMaximumLength(CommentModel::getTableName(), 'email');
        $fieldMaxLength['text'] = DbHelper::obj()->getCharacterMaximumLength(CommentModel::getTableName(), 'text');

        $imageParams = ConfigHelper::getInstance()->getConfig()['site']['comments']['creation_settings']['image'];

        $this->render(
            'index',
            [
                'comments' => $comments->rows,
                'orderTypes' => $orderTypes,
                'fieldMaxLength' => $fieldMaxLength,
                'imageParams' => $imageParams,
                'maxFileSize' => ConfigHelper::getInstance()->getConfig()['site']['comments']['creation_settings']['max_file_size']
            ]
        );
    }

    /*public function actionUpdate()
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
    }*/

}