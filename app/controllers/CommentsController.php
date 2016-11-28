<?php

class CommentsController extends ControllerController
{
    /** @var bool показывать ли интерфейс администратора */
    protected $ifAdmin;

    public function _construct($ifAdmin = false)
    {
        $this->ifAdmin = $ifAdmin;
    }

    /**
     * Валидация данных о комментарии.
     *
     * @param array $data подготовленная информация о комментарии (к примеру $_POST запроса)
     * @return array пустой массив в случае успеха или содержит элемент ['errors'] с массивами ошибок
     */
    protected function validateCommentIncomingData($data)
    {
        $res = [];

        $validationData = $data;

        if (!empty($_FILES['image']['tmp_name']))
        {
            if ($_FILES['image']['size'] > ConfigHelper::getInstance()->getConfig(
                )['site']['comments']['creation_settings']['max_file_size']
            )
            {
                #CommonHelper::sendJsonResponse(false, ['message' => 'Вы пытались загрузить слишком большой файл.']);
                $res['errors']['input_data'][] = [
                    'field' => 'image',
                    'message' => 'Вы пытались загрузить слишком большой файл.'
                ];
            }
            else
            {
                $validationData['image'] = $_FILES['image']['tmp_name'];
            }
        }

        if ($validationResult = $this->commentDataValidation($validationData))
        {
            $res = array_merge_recursive($res, $validationResult);
            #CommonHelper::sendJsonResponse(false, ['message' => 'Не пройдена валидация.']);
        }

        return $res;
    }

    /*protected function mergeTableFieldsAndIncomingData($data)
    {
        $fields = [];
        $sqlFields = DbHelper::obj()->getFieldsName(CommentModel::getTableName());
        foreach ($sqlFields as $fld)
        {
            $fields[$fld->COLUMN_NAME] = '';
        }
        $fields = array_merge($fields, $data);

        return $fields;
    }*/

    public function actionGet()
    {
        $orderBy = (isset($_GET['comments']['order']['by']) && in_array(
                CommentModel::getValidOrderFields(),
                $_GET['comments']['order']['by'],
                true
            )) ? $_GET['comments']['order']['by'] : 'created';

        $orderDir = (isset($_GET['comments']['order']['dir']) && DbHelper::obj()->ifValidOrderDirection(
                $_GET['comments']['order']['dir']
            )) ? $_GET['comments']['order']['dir'] : 'DESC';

        $comments = CommentModel::getComments($orderBy, $orderDir);

        $html = $this->renderPartial(
            'comment_list',
            [
                'comments' => $comments->rows
            ]
        );

        CommonHelper::sendHtmlResponse($html);
    }

    public function actionNew()
    {
        $fields = DbHelper::obj()->getFieldsName(CommentModel::getTableName());

        $fields = array_flip($fields);

        array_walk(
            $fields,
            function (&$item1)
            {
                $item1 = '';
            }
        );

        $fields = array_merge($fields, $_POST);

        $fields['username'] = trim($fields['username']);
        $fields['email'] = trim($fields['email']);

        if ($isNotValid = $this->validateCommentIncomingData($fields))
        {
            CommonHelper::sendJsonResponse(false, $isNotValid);
        }
        //$fields = $this->mergeTableFieldsAndIncomingData($data);

        $fields['images_data'] = [];

        if (!empty($_FILES['image']['tmp_name']))
        {
            if (is_uploaded_file($_FILES['image']['tmp_name']))
            {
                if (($images = ImageHelper::reduceImageToMaxDimensions($_FILES['image']['tmp_name'], true, true))
                    && (!empty($images['new']) && !empty($images['new_thumb']))
                )
                {
                    $imgPath = empty($_GET['preview']) ? '/images/comments/images/' : '/images/comments/images_temp/';

                    $images['new']['src'] = $imgPath . $images['new']['name'];
                    $images['new_thumb']['src'] = $imgPath . $images['new_thumb']['name'];

                    $fields['images_data']['image'] = $images['new'];
                    $fields['images_data']['image_thumb'] = $images['new_thumb'];

                    $fields['image_name'] = $images['new']['name'];
                }
                else
                {
                    LoggerComponent::getInstance()->log('Ошибка сохранения загруженного файла.');
                    CommonHelper::sendJsonResponse(
                        false,
                        ['errors' => ['common' => ['Ошибка сохранения загруженного файла']]]
                    );
                }
            }
            else
            {
                LoggerComponent::getInstance()->log('Ошибка загрузки файла.');
                CommonHelper::sendJsonResponse(false, ['errors' => ['common' => ['Ошибка загрузки файла']]]);
            }
        }

        if (empty($_GET['preview']))
        {
            if (!CommentModel::setNewComment($fields))
            {
                LoggerComponent::getInstance()->log('Не удалось сохранить комментарий.');
                CommonHelper::sendJsonResponse(false, ['errors' => ['common' => ['Не удалось сохранить комментарий']]]);
            }
        }

        $html = $this->renderPartial(
            '_comment',
            ['item' => $fields]
        );

        CommonHelper::sendHtmlResponse($html);
    }

    /**
     * Валидация полей комментариев.
     *
     * @param array $data список полей со значениями
     * @return array пустой массив в случае успеха или содержит элемент ['errors'] с массивами ошибок
     */
    protected function commentDataValidation($data)
    {
        $ret = [];

        $config = ConfigHelper::getInstance()->getConfig();

        $encoding = $config['globalEncoding'];
        $sizes = $config['site']['comments']['creation_settings']['text_sizes'];

        $lName = mb_strlen($data['username'], $encoding);
        $lEmail = mb_strlen($data['email'], $encoding);
        $lText = mb_strlen($data['text'], $encoding);

        if (($lName < $sizes['username']['min'] || $lName > $sizes['username']['max'])
            || ($lEmail < $sizes['email']['min'] || $lEmail > $sizes['email']['max'])
            || ($lText < $sizes['text']['min'] && $lText > $sizes['text']['max'])
            || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
        )
        {
            // TODO: надо сделать вывод ошибки по каждому отдельному полю (в input_data), пока пусть будет общая ошибка
            // т. к. валидация должна проходить в js на стороне клиента (кроме изображений, которые на стороне клиента
            // проверить невозможно).
            $ret['errors']['common'][] = 'Ошибка валидации одного или нескольких полей';
        }

        if (!empty($data['image']) && ($imageResult = ImageHelper::validateCommentImage($data['image'])))
        {
            $ret['errors']['input_data'][] = [
                'field' => 'image',
                'message' => $imageResult['error']
            ];
        }

        return $ret;
    }

    public function actionIndex()
    {
        $this->title = CommonHelper::createTitle('список комментариев');
        $this->scripts['css'] .= CommonHelper::createCssLink('/css/comments.css');
        $this->scripts['js'] .= CommonHelper::createJsLink('/js/comments.js');

        $orderFields = CommentModel::getValidOrderFields();
        $orderLabels = CommentModel::getLabels();
        $orderTypes = [];
        foreach ($orderFields as $ot)
        {
            $orderTypes[] = ['id' => $ot, 'name' => $orderLabels[$ot]];
        }

        $fieldMaxLength = [];

        $confTS = ConfigHelper::getInstance()->getConfig()['site']['comments']['creation_settings']['text_sizes'];

        $fieldMaxLength['username'] = $confTS['username']['max'];
        $fieldMaxLength['email'] = $confTS['email']['max'];
        $fieldMaxLength['text'] = $confTS['text']['max'];

        $fieldMinLength['username'] = $confTS['username']['min'];
        $fieldMinLength['email'] = $confTS['email']['min'];
        $fieldMinLength['text'] = $confTS['text']['min'];

        $allowedRangeAlert['username'] = sprintf(
            _('Допустимое количество знаков в имени пользователя: [%d, %d]'),
            $fieldMinLength['username'],
            $fieldMaxLength['username']
        );
        $allowedRangeAlert['email'] = sprintf(
            _('Допустимое количество знаков в email: [%d, %d]'),
            $fieldMinLength['email'],
            $fieldMaxLength['email']
        );
        $allowedRangeAlert['text'] = sprintf(
            _('Допустимое количество знаков в сообщении: [%d, %d]'),
            $fieldMinLength['text'],
            $fieldMaxLength['text']
        );

        $wrongEmailAlert = _("Неправильный формат email");

        $imageParams = ConfigHelper::getInstance()->getConfig()['site']['comments']['creation_settings']['image'];

        $this->render(
            'index',
            [
                'orderTypes' => $orderTypes,
                'fieldMaxLength' => $fieldMaxLength,
                'fieldMinLength' => $fieldMinLength,
                'allowedRangeAlert' => $allowedRangeAlert,
                'wrongEmailAlert' => $wrongEmailAlert,
                'imageParams' => $imageParams,
                'maxFileSize' => ConfigHelper::getInstance()->getConfig(
                )['site']['comments']['creation_settings']['max_file_size']
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