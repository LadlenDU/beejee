<div class="row" data-id="<?php echo $item['id'] ?>">

    <?php if ($item['modified']): ?>
    <div class="admin_modified">Изменен администратором</div>
    <?php endif; ?>

    <?php if ($item['images_data']): ?>
    <div class="image">
        <img class="thumb_image" data-preview-src="<?php echo $item['images_data']['image']['src'] ?>" width="<?php echo $item['images_data']['image_thumb']['width'] ?>"
             height="<?php echo $item['images_data']['image_thumb']['height'] ?>"
             src="<?php echo $item['images_data']['image_thumb']['src'] ?>"
             alt="Прикрепленное изображение"
             title="Нажмите для полного просмотра"/>
    </div>
    <?php endif; ?>

    <div class="name">
        <div class="capt">Имя:</div>
        <div class="value"><?php $item['username'] ?></div>
    </div>
    <div class="email">
        <div class="capt">Email:</div>
        <div class="value"><?php $item['email'] ?></div>
    </div>


</div>