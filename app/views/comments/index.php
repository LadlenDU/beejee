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

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">Список сообщений</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <form class="navbar-form navbar-right">
                <!--<div class="form-group">
                    <input placeholder="Email" class="form-control" type="text">
                </div>
                <div class="form-group">
                    <input placeholder="Password" class="form-control" type="password">
                </div>
                <button type="submit" class="btn btn-success">Sign in</button>-->
                <?php (new AdminWidget)->renderLogoutItem(); ?>
                <?php if(0): ?>
                    <a class="navbar-brand" href="/">lsdkjflksdf</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-2">Список:</div>
        <div class="col-md-5"><?php #echo $content ?></div>
        <div class="col-md-5"><?php #echo $content ?></div>
    </div>
</div>

<div id="order_by">
    Сортировать по:
    <select name="order_by">
        <?php foreach ($orderTypes as $o): ?>
        <option value="<?php echo $o['id'] ?>"><?php echo htmlspecialchars($o['name'], ENT_QUOTES, ConfigHelper::getConfig()['globalEncoding']) ?></option>
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


