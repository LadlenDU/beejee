<div id="new_comment">
    <div class="row caption">Создать комментарий:</div>
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