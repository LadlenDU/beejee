<?php echo CommonWidget::headerPanel() ?>

<div class="container">
    <div class="row vertical-offset-100">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Войти на сайт</h3>
                </div>
                <div class="panel-body">
                    <?php echo FormWidget::startForm(
                        [
                            'accept-charset' => 'UTF-8',
                            'role' => 'form',
                            'method' => 'POST',
                            'action' => '/user/login'
                        ]
                    ) ?>
                        <fieldset
                            <?php if ($wrong_login): ?>
                                class="has-error"
                            <?php endif; ?>
                            >
                            <div class="form-group">
                                <input class="form-control" placeholder="Логин" name="login" type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Пароль" name="password" value="" type="password">
                            </div>

                            <?php if ($wrong_login): ?>
                                <div class="form-group">Неправильный логин или пароль</div>
                            <?php endif; ?>

                            <input class="btn btn-lg btn-success btn-block" value="Логин" type="submit">
                        </fieldset>
                    <?php echo FormWidget::endForm() ?>
                </div>
            </div>
        </div>
    </div>
</div>