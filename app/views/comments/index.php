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

        <div class="row order">
            <div class="col-xs-7">
                <div class="row">
                    <div class="col-xs-5 text-nowrap">Сортировать по:</div>
                    <div class="col-xs-5">
                        <select name="order_by">
                            <?php foreach ($orderTypes as $o): ?>
                                <option value="<?php echo $o['id'] ?>">
                                    <?php echo CommonHelper::_h($o['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-xs-5">
                        <select name="order_direction">
                            <option value="asc">По возрастанию</option>
                            <option value="desc">По убыванию</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-xs-5"></div>
        </div>

        <div class="row">
            <?php if (!count($comments)): ?>
                <p class="text-center">НЕТ СООБЩЕНИЙ</p>
            <?php endif ?>
            <?php foreach ($comments as $item): ?>
                <?php echo $this->renderPhpFile(dirname(__FILE__) . '/_comment.php', ['item' => $item]); ?>
            <?php endforeach ?>
        </div>

    </div>


    <div class="col-md-5">

        <div id="new_comment">
            <div class="row caption">Создайте новый комментарий:</div>
            <div class="row">
                <form enctype="multipart/form-data" action="/addComment" method="POST" id="form_comment">
                    <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
                    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                    <!-- Название элемента input определяет имя в массиве $_FILES -->
                    Прикрепить изображение (допустимые форматы: JPG, GIF, PNG): <input name="image" type="file" />

                Ваше имя: <input type="text" name="name" maxlength="<?php echo $fieldMaxLength['name'] ?>" />
                Ваш email: <input type="text" name="email" maxlength="<?php echo $fieldMaxLength['email'] ?>" />
                Текст сообщения:
                <textarea name="text" maxlength="<?php echo $fieldMaxLength['text'] ?>"></textarea>

                    <input type="submit" id="comment_preview" value="Предварительный просмотр"/>
                    <input type="submit" name="send_comment" id="send_comment" value="Отправить"/>

                </form>
            </div>
        </div>

    </div>

</div>

