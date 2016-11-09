<?php

/**
 * Class CsrfHelper
 *
 * Класс для защиты от CSRF атак.
 */
class CsrfHelper extends SingletonHelper
{
    protected $requestMethodsToCheck = ['POST', 'PUT', 'DELETE'];

    public function getCsrfToken()
    {
        if (!$csrfToken = $this->loadCsrfToken())
        {
            $salt = ConfigHelper::getConfig()['csrf']['salt'];

            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-.';
            $mask = substr(str_shuffle(str_repeat($chars, 5)), 0, 8);
            // The + sign may be decoded as blank space later, which will fail the validation
            $secret = str_replace('+', '.', base64_encode($mask));

            $csrfToken = $salt . ':' . md5($salt + ':' + $secret);
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

        $tokenName = ConfigHelper::getConfig()['csrf']['tokenName'];

        assert($_SERVER['REQUEST_METHOD']);

        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        if (in_array($method, $this->requestMethodsToCheck))
        {
            $method = '_' . $method;
            $ret = ($$method[$tokenName] == $this->loadCsrfToken() || $_COOKIE[$tokenName] == $this->loadCsrfToken());
        }

        return $ret;
    }
}