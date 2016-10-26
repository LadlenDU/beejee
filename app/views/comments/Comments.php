<?php
define('CLICK_TO_MOD', 'Нажмите чтобы редактировать');
#print_r($model);
?>

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

<div id="order_by">
    Сортировать по:
    <select name="order_by">
        <?php foreach ($orderTypes as $o): ?>
        <option value="<?php echo $o['id'] ?>"><?php echo htmlspecialchars($o['name'], ENT_QUOTES, DOCUMENT_ENCODING) ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div id="comment_list">
    <div id="list">
        <?php if (!count($comments)): ?>
            <div id="no_comments">НЕТ СООБЩЕНИЙ</div>
        <?php endif ?>
        <?php foreach ($comments as $item): ?>
            <?php echo $this->renderPhpFile(dirname(__FILE__) . '/_comment.php', ['item' => $item]); ?>
        <?php endforeach ?>
    </div>
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


