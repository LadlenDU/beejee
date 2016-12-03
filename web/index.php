<?php

if (version_compare(phpversion(), '5.4.0', '<') == true)
{
    die(_('Please use version of PHP not less than 5.4.'));
}

define('WEB_DIR', __DIR__ . '/');
define('APP_DIR', realpath(__DIR__ . '/../app') . '/');
#define('APP_DIR', __DIR__ . '/app/');
die(APP_DIR);
require(APP_DIR . 'core/Autoloader.php');

$config = ConfigHelper::getInstance()->getConfig();

if ($config['debug'])
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
else
{
    error_reporting(0);
}

try
{
    CommonHelper::startSession();

    if (!CsrfHelper::getInstance()->validateCsrfToken())
    {
        throw new Exception('Ошибка CSRF токена');
    }

    (new RouterComponent())->run();
    #UserComponent::getInstance()->logIn('admin', '123');
    #$roles = UserComponent::getInstance()->getUserRoles();
}
catch (Exception $e)
{
    if (ConfigHelper::getInstance()->getConfig()['debug'])
    {
        $msg = sprintf(
            _("Error occured.\nCode: %s.\nMessage: %s.\nFile: %s.\nLine: %s.\nTrace: %s\n"),
            $e->getCode(),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
    }
    else
    {
        $msg = _('Server error');
    }

    LoggerComponent::getInstance()->log($msg);

    if (CommonHelper::ifAjax())
    {
        CommonHelper::sendJsonResponse(false, $msg);
    }
    else
    {
        header('Content-Type: text/plain; charset=' . ConfigHelper::getInstance()->getConfig()['globalEncoding']);
        die($msg);
    }
}
