<?php

/**
 * Class CsrfHelper
 *
 * Класс для защиты от CSRF атак.
 */
class CsrfHelper extends SingletonHelper
{
    /** @var array методы при которых нужна проверка на CSRF */
    protected $requestMethodsToCheck = ['POST', 'PUT', 'DELETE'];

    /** @var array методы при которых НЕ нужна проверка на CSRF */
    protected $requestMethodsAllowed = ['GET'];

    public function getCsrfTokenName()
    {
        return ConfigHelper::getInstance()->getConfig()['csrf']['tokenName'];
    }

    /**
     * Создает и сохраняет CSRF токен если надо.
     *
     * @return null|string
     */
    public function getCsrfToken()
    {
        if (!$csrfToken = $this->loadCsrfToken())
        {
            $salt = ConfigHelper::getInstance()->getConfig()['csrf']['salt'];
            $csrfToken = $salt . ':' . md5($salt + ':' + uniqid('csrf_comments', true));
            $this->storeCsrfToken($csrfToken);
        }

        return $csrfToken;
    }

    protected function storeCsrfToken($csrfToken)
    {
        CommonHelper::startSession();
        $_SESSION['csrf']['token'] = $csrfToken;
    }

    protected function loadCsrfToken()
    {
        CommonHelper::startSession();
        return (isset($_SESSION['csrf']['token'])) ? $_SESSION['csrf']['token'] : null;
    }

    /**
     * Проверка на правильность CSRF токена.
     *
     * @return bool
     */
    public function validateCsrfToken()
    {
        $ret = false;

        $tokenName = ConfigHelper::getInstance()->getConfig()['csrf']['tokenName'];

        if (!empty($_SERVER['REQUEST_METHOD']))
        {
            $method = strtoupper($_SERVER['REQUEST_METHOD']);
            if (in_array($method, $this->requestMethodsToCheck))
            {
                if (!$this->loadCsrfToken())
                {
                    // Возможно закончилась сессия на сервере.
                    CommonHelper::redirect('?session_expired=true');
                }

                $method = '_' . $method;
                global ${$method};
                $ret = ((isset(${$method}[$tokenName]) && ${$method}[$tokenName] == $this->loadCsrfToken())
                    || (isset($_COOKIE[$tokenName]) && $_COOKIE[$tokenName] == $this->loadCsrfToken()));
            }
            elseif (in_array($method, $this->requestMethodsAllowed))
            {
                $ret = true;
            }
        }

        return $ret;
    }
}