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
    <div class="col-md-2 hidden-xs hidden-sm bg-info"><p class="text-center">Список:</p></div>
    <div class="col-md-5">

        <div class="row order container-fluid">
            <div class="col-md-4 col-sm-4 text-nowrap narrow-sides">Сортировать по:</div>
            <div class="col-md-4 col-sm-4 narrow-sides">
                <select name="order_by">
                    <?php foreach ($orderTypes as $o): ?>
                        <option value="<?php echo $o['id'] ?>">
                            <?php echo CommonHelper::_h($o['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 col-sm-4 narrow-sides">
                <select name="order_direction">
                    <option value="asc">По возрастанию</option>
                    <option value="desc">По убыванию</option>
                </select>
            </div>
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


    <div class="col-md-5">

        <div class="row">Новый комментарий:</div>
        <div class="row container-fluid">
            <?php echo FormWidget::startForm(
                ['enctype' => 'multipart/form-data', 'action' => '/comments/add', 'id' => 'form_comment']
            ) ?>
                <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />

            <div class="row">
                Прикрепить изображение (допустимые форматы: JPG, GIF, PNG):<br>
                <input name="image" type="file"
                       accept=".jpg, .gif, .png, image/jpeg, image/gif, image/png"/>
            </div>

            <div class="row">
                Имя: <input type="text" name="name" maxlength="<?php echo $fieldMaxLength['name'] ?>" />
            </div>
            <div class="row">
                Email: <input type="text" name="email" maxlength="<?php echo $fieldMaxLength['email'] ?>" />
            </div>
            <div class="row">
                Текст сообщения:<br>
                <textarea name="text" maxlength="<?php echo $fieldMaxLength['text'] ?>"></textarea>
            </div>
            <div class="row">
                <div class="col-md-8 col-sm-4">
                    <input type="submit" id="comment_preview" value="Предварительный просмотр"/>
                </div>
                <div class="col-md-4 col-sm-4">
                    <input type="submit" id="send_comment" name="send_comment" value="Отправить"/>
                </div>
            </div>

            <?php echo FormWidget::endForm() ?>
        </div>

    </div>

</div>

