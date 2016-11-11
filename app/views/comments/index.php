<?php
#define('CLICK_TO_MOD', 'Нажмите чтобы редактировать');
#print_r($model);

?>
<?php echo CommonWidget::headerPanel() ?>

<script type="text/javascript">
    $(function () {
        $('#form_comment').click(function(e) {

            if ($(document.activeElement).attr('id') == 'comment_preview') {
                var formData = new FormData(this);
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '/comments/preview',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        logInfo("success");
                        logInfo(data);
                    },
                    error: function (data) {
                        logInfo("error");
                        logInfo(data);
                    }
                });
            }
        });
    });
</script>

<div class="row wrapper">
    <div class="col-md-2 hidden-xs hidden-sm list_caption main_column"><p class="text-center">Список:</p></div>
    <div class="col-md-5 main_column">

        <div class="row order container-fluid">

            <?php echo FormWidget::startForm(
                ['class' => 'form-horizontal', 'id' => 'form_order']
            ) ?>

            <div class="col-md-4 col-sm-4 narrow-sides"><label class="text-nowrap control-label">Сортировать по:</label></div>
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
                    <option value="asc">Возрастанию</option>
                    <option value="desc">Убыванию</option>
                </select>
            </div>

            <?php echo FormWidget::endForm() ?>

        </div>

        <div class="row container-fluid">
            <?php if (!count($comments)): ?>
                <p class="text-center">НЕТ СООБЩЕНИЙ</p>
            <?php endif ?>
            <?php foreach ($comments as $item): ?>
                <?php echo $this->renderPhpFile(dirname(__FILE__) . '/_comment.php', ['item' => $item]); ?>
            <?php endforeach ?>
        </div>

    </div>

    <div class="clearfix visible-xs-block"></div>

    <div class="col-md-5 create_comment main_column">

        <div class="row container-fluid"><h4>Новый комментарий:</h4></div>
        <div class="row container-fluid">
            <?php echo FormWidget::startForm(
                ['enctype' => 'multipart/form-data', 'action' => '/comments/add', 'id' => 'form_comment']
            ) ?>
                <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />

            <!--<div class="row container-fluid">
                Прикрепить изображение (допустимые форматы: JPG, GIF, PNG):<br>
                <input name="image" type="file"
                       accept=".jpg, .gif, .png, image/jpeg, image/gif, image/png"/>
            </div>

            <div class="row container-fluid">
                <input type="text" name="name" maxlength="<?php echo $fieldMaxLength['name'] ?>" placeholder="Имя" class="form-control input-sm" />
            </div>
            <div class="row container-fluid">
                <input type="text" name="email" maxlength="<?php echo $fieldMaxLength['email'] ?>" placeholder="Email" class="form-control input-sm" />
            </div>
            <div class="row container-fluid">
                Текст сообщения:<br>
                <textarea name="text" maxlength="<?php echo $fieldMaxLength['text'] ?>" rows="5" class="form-control"></textarea>
            </div>
            <div class="row container-fluid">
                <div class="col-md-8 col-sm-4">
                    <input type="submit" id="comment_preview" value="Предварительный просмотр" class="btn btn-default"/>
                </div>
                <div class="col-md-4 col-sm-4">
                    <input type="submit" id="send_comment" name="send_comment" value="Отправить" class="btn btn-default"/>
                </div>
            </div>-->

            <div class="form-group">
                <label for="file_image">Прикрепить изображение:</label>
                <input name="image" type="file" id="file_image"
                       accept=".jpg, .gif, .png, image/jpeg, image/gif, image/png"/>
                <p class="help-block">Допустимые форматы: JPG, GIF, PNG</p>
            </div>

            <div class="form-group">
                <label for="name">Имя:</label>
                <input id="name" type="text" name="name" maxlength="<?php echo $fieldMaxLength['name'] ?>" placeholder="Имя" class="form-control" />
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input id="email" type="text" name="email" maxlength="<?php echo $fieldMaxLength['email'] ?>" placeholder="Email" class="form-control" />
            </div>

            <div class="form-group">
                <label for="text">Текст сообщения:</label>
                <textarea id="text" name="text" maxlength="<?php echo $fieldMaxLength['text'] ?>" rows="5" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <div class="col-md-8 col-sm-4">
                    <input type="submit" id="comment_preview" value="Предварительный просмотр" class="btn btn-default"/>
                </div>
                <div class="col-md-4 col-sm-4">
                    <input type="submit" id="send_comment" name="send_comment" value="Отправить" class="btn btn-default"/>
                </div>
            </div>


            <?php echo FormWidget::endForm() ?>
        </div>

    </div>

</div>

