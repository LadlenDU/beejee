<?php #(new CommentsController($this->config))->actionIndex() ?>
<?php echo CommonWidget::headerPanel() ?>

Вы на заглавной странице.
Перейдите на любую из страниц:
<ul>
    <li><a href="/comments">Страница комментариев</a></li>
    <li><a href="/user/login">Залогиниться</a></li>
</ul>
На данный момент есть только один пользователь - администратор (логин "admin", пароль "123").<br>
После чего станет доступна <a href="/comments/admin">страница администратора</a>, которая похожа на <a href="/comments">страницу комментариев</a>, но с элементами управления для администрирования.

