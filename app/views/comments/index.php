<?php echo CommonWidget::headerPanel() ?>

<script type="text/javascript">
    $(function(){
        comments.elements.lengths.username.min = 1;
        comments.elements.lengths.username.max = <?php echo $fieldMaxLength['username'] ?>;
        comments.elements.lengths.email.min = 5;
        comments.elements.lengths.email.max = <?php echo $fieldMaxLength['email'] ?>;
        comments.elements.lengths.text.min = 1;
        comments.elements.lengths.text.max = <?php echo $fieldMaxLength['text'] ?>;
    });

    /*$(function () {
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
    });*/
</script>

<div class="row wrapper">
    <div class="col-md-2 hidden-xs hidden-sm list_caption main_column"><p class="text-center">Список:</p></div>
    <div class="col-md-5 main_column">

        <div class="row order">

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

        <div class="row container-fluid"><h4>Новый комментарий:</h4></div>
        <div class="row container-fluid">
            <?php echo FormWidget::startForm(
                ['enctype' => 'multipart/form-data', 'action' => '/comments/add', 'id' => 'form_comment']
            ) ?>

            <div class="form-group">
                <label for="username">Имя:</label>
                <input id="username" type="text" name="username" maxlength="<?php echo $fieldMaxLength['username'] ?>" placeholder="Имя" class="form-control" />
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
                <label for="file_image">Прикрепить изображение:</label>
                <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxFileSize ?>" />
                <input name="image" type="file" id="file_image"
                       accept="<?php echo $imageParams['types_allowed'] ?>"/>
                <p class="help-block">Допустимые форматы: JPG, GIF, PNG</p>
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

