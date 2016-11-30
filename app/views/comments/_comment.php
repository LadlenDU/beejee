<div class="comment_panel" data-id="<?php echo $item['id'] ?>">

    <?php if ($item['modified']): ?>
        <div class="admin_modified">Изменен администратором [ <div class="time"><?php echo $item['modified'] ?></div> ]</div>
    <?php endif; ?>

    <div class="name-email"><?php echo CommonHelper::h($item['username']) ?> (<a href="mailto:<?php echo CommonHelper::h($item['email']) ?>"><?php echo CommonHelper::h($item['email']) ?></a>)</div>
    Написал(а): <?php if ($item['created']) echo '[ <div class="time">' . $item['created'] . '</div> ]'; ?>

    <div class="text">
        <div class="value"><?php echo nl2br(CommonHelper::h($item['text'])) ?></div>
    </div>

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
                <option value="UNDEFINED">Не определен</option>
                <option value="APPROVED">Принят</option>
                <option value="REJECTED">Отклонен</option>
            </select>
        </div>
    <?php endif; ?>

</div>