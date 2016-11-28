<div class="comment_panel" data-id="<?php echo $item['id'] ?>">

    <?php if ($item['modified']): ?>
    <div class="admin_modified">Изменен администратором</div>
    <?php endif; ?>

    <div class="name-email"><?php echo CommonHelper::h($item['username']) ?> (<a href="mailto:<?php echo CommonHelper::h($item['email']) ?>"><?php echo CommonHelper::h($item['email']) ?></a>)</div>
    Написал(а):

    <div class="text">
        <div class="value"><?php echo CommonHelper::h($item['text']) ?></div>
    </div>

    <?php if ($item['images_data']): ?>
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

</div>