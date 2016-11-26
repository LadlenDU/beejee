<?php echo CommonWidget::headerPanel() ?>

<script type="text/javascript">
    //var comments = {};
    /*var comments = {
        elements: {
            lengths: {
                username: {
                    min:<?php echo json_encode($fieldMinLength['username']) ?>,
                    max:<?php echo json_encode($fieldMaxLength['username']) ?>,
                    range_alert:<?php echo json_encode($allowedRangeAlert['username']) ?>
                },
                email: {
                    min:<?php echo json_encode($fieldMinLength['email']) ?>,
                    max:<?php echo json_encode($fieldMaxLength['email']) ?>,
                    range_alert:<?php echo json_encode($allowedRangeAlert['email']) ?>,
                    wrong_email_alert:<?php echo json_encode($wrongEmailAlert) ?>
                },
                text: {
                    min:<?php echo json_encode($fieldMinLength['text']) ?>,
                    max:<?php echo json_encode($fieldMaxLength['text']) ?>,
                    range_alert:<?php echo json_encode($allowedRangeAlert['text']) ?>
                }
            }
        }
    };*/
</script>

<div class="row wrapper">

    <div class="col-md-2 hidden-xs hidden-sm list_caption main_column"><p class="text-center">Список:</p></div>

    <div class="col-md-5 main_column">
        <div class="row order">

            <?php echo FormWidget::startForm(
                ['class' => 'form-horizontal', 'id' => 'form_order']
            ) ?>

            <div class="col-md-4 col-sm-4 narrow-sides"><label class="text-nowrap control-label">Сортировать по:</label>
            </div>
            <div class="col-md-4 col-sm-4 narrow-sides">
                <select name="order_by" class="form-control">
                    <?php foreach ($orderTypes as $o): ?>
                        <option value="<?php echo $o['id'] ?>">
                            <?php echo CommonHelper::_h($o['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 col-sm-4 narrow-sides">
                <select name="order_direction" class="form-control">
                    <option value="asc">Возрастание</option>
                    <option value="desc">Убывание</option>
                </select>
            </div>

            <?php echo FormWidget::endForm() ?>

        </div>

        <div class="row" id="preview_messages"></div>
        <div class="row messages">
            <?php if (!count($comments)): ?>
                <p class="text-center">НЕТ СООБЩЕНИЙ</p>
            <?php endif ?>
            <?php foreach ($comments as $item): ?>
                <?php echo $this->renderPhpFile(dirname(__FILE__) . '/_comment.php', ['item' => $item]); ?>
            <?php endforeach ?>
        </div>

    </div>

    <div class="col-md-5 create_comment main_column">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><h4>Новый комментарий:</h4></div>
            </div>
            <div class="panel-body">
                <div class="row container-fluid">

                    <?php echo FormWidget::startForm(
                        ['enctype' => 'multipart/form-data', 'action' => '/comments/add', 'id' => 'form_comment']
                    ) ?>

                    <div class="form-group required">
                        <label for="username" class="control-label">Имя:</label>
                        <input autofocus="autofocus" id="username" type="text" name="username"
                               data-minlength="<?php echo $fieldMinLength['username'] ?>"
                               data-range-alert="<?php echo CommonHelper::h($allowedRangeAlert['username']) ?>"
                               maxlength="<?php echo $fieldMaxLength['username'] ?>" placeholder="Имя"
                               class="form-control"/>

                        <p class="help-block help-block-error"></p>
                    </div>

                    <div class="form-group required ">
                        <label for="email" class="control-label">Email:</label>
                        <input id="email" type="text" name="email" maxlength="<?php echo $fieldMaxLength['email'] ?>"
                               data-minlength="<?php echo $fieldMinLength['email'] ?>"
                               data-range-alert="<?php echo CommonHelper::h($allowedRangeAlert['email']) ?>"
                               data-wrong-email-alert="<?php echo CommonHelper::h($wrongEmailAlert) ?>"
                               placeholder="Email" class="form-control"/>

                        <p class="help-block help-block-error"></p>
                    </div>

                    <div class="form-group required ">
                        <label for="text" class="control-label">Текст сообщения:</label>
                        <textarea id="text" name="text" maxlength="<?php echo $fieldMaxLength['text'] ?>" rows="5"
                                  data-minlength="<?php echo $fieldMinLength['text'] ?>"
                                  data-range-alert="<?php echo CommonHelper::h($allowedRangeAlert['text']) ?>"
                                  class="form-control"></textarea>

                        <p class="help-block help-block-error"></p>
                    </div>

                    <div class="form-group">
                        <label for="file_image">Прикрепить изображение:</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxFileSize ?>"/>
                        <input name="image" type="file" id="file_image"
                               accept="<?php echo $imageParams['types_allowed'] ?>"/>

                        <p class="help-block">Допустимые форматы: JPG, GIF, PNG</p>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-sm-4 comment_preview_btn">
                            <input type="submit" id="comment_preview" value="Предварительный просмотр"
                                   class="btn btn-default"/>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <input type="submit" id="send_comment" name="send_comment" value="Отправить"
                                   class="btn btn-default"/>
                        </div>
                    </div>

                    <?php echo FormWidget::endForm() ?>
                </div>
            </div>
        </div>
    </div>

</div>

