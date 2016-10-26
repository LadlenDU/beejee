<?php

class Controller
{
    public function __construct()
    {
        $actionName = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index';
        $functionName = 'action' . ucfirst($actionName);
        if(method_exists($this, $functionName))
        {
            $this->$functionName();
        }
    }

    protected function renderPhpFile($file, $params = [])
    {
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        require($file);

        return ob_get_clean();
    }

    protected function render($file, $params)
    {
        $content = $this->renderPhpFile($file, $params);
        require(APP_DIR . 'views/layout.php');
    }
}