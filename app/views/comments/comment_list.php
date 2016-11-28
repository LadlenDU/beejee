<?php if (!count($comments)): ?>
    <p class="text-center">НЕТ СООБЩЕНИЙ</p>
<?php endif ?>
<?php foreach ($comments as $item): ?>
    <?php echo $this->renderPhpFile(dirname(__FILE__) . '/_comment.php', ['item' => $item]); ?>
<?php endforeach ?>