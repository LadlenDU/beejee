<div class="comment_panel" data-id="<?php echo $item['id'] ?>" xmlns="http://www.w3.org/1999/html">

    <div class="name-email"><?php echo CommonHelper::h($item['username']) ?> (<a href="mailto:<?php echo CommonHelper::h($item['email']) ?>"><?php echo CommonHelper::h($item['email']) ?></a>)</div>
    Написал(а): <?php if ($item['created']) echo '[ <div class="time">' . $item['created'] . '</div> ]'; ?>

    <?php if ($item['modified']): ?>
        <div class="admin_modified text-info">Изменен администратором [ <div class="time"><?php echo $item['modified'] ?></div> ]</div>
    <?php endif; ?>

    <div class="text">
        <div class="value well well-sm"><?php echo nl2br(CommonHelper::h($item['text'])) ?></div>
        <textarea style="display: none" class="text_mod" rows="5"><?php echo CommonHelper::h($item['text']) ?></textarea>
    </div>

    <?php if ($this->ifAdmin): ?>
        <div class="edit_text_wrapper">
            <button type="button" class="btn btn-default btn-sm edit">Редактировать текст</button>
            <button type="button" class="btn btn-default btn-sm cancel" style="display: none">Отменить</button>
            <button type="button" class="btn btn-default btn-sm save" style="display: none">Сохранить</button>
        </div>
    <?php endif; ?>

    <?php if (!empty($item['images_data'])): ?>
        <a class="fancybox" href="<?php echo $item['images_data']['image']['src'] ?>">
            <div class="image row">
                <img class="thumb_image" data-preview-src="<?php echo $item['images_data']['image']['src'] ?>" width="<?php echo $item['images_data']['image_thumb']['width'] ?>"
                     height="<?php echo $item['images_data']['image_thumb']['height'] ?>"
                     src="<?php echo $item['images_data']['image_thumb']['src'] ?>"
                     alt="Прикрепленное изображение"
                     title="Нажмите для полного просмотра"/>
            </div>
        </a>
    <?php endif; ?>

    <?php if ($this->ifAdmin): ?>
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Статус:</span>
            <select class="form-control" name="comment_status">
                <option value="UNDEFINED" <?php if ($item['status'] == 'UNDEFINED'): ?>
                    selected="selected"
            <?php endif; ?>>Не определен</option>
                <option value="APPROVED"<?php if ($item['status'] == 'APPROVED'): ?>
                    selected="selected"
                <?php endif; ?>>Принят</option>
                <option value="REJECTED"<?php if ($item['status'] == 'REJECTED'): ?>
                    selected="selected"
                <?php endif; ?>>Отклонен</option>
            </select>
        </div>
    <?php endif; ?>

</div>