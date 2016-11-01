<?php

/**
 * Class RouterHelper
 *
 * Работа с роутингом.
 */
class RouterHelper
{
    /**
     * URL части страницы по умолчанию.
     * @var array
     */
    protected $pageDefault = ['controller' => 'default', 'action' => 'index'];

    /**
     * URL части для не найденной страницы.
     * @var array
     */
    protected $page404 = ['controller' => 'default', 'action' => '404'];

    /**
     * Configuration.
     * @var array
     */
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Проанализировать путь и найти по нему контроллер и действие.
     * Если контроллер или действие не найдено, возвращает контроллер и действие к странице 404.
     *
     * @param array $path строка пути, разделенная в массив
     * @return array
     */
    protected function getControllerActionName($path)
    {
        $ret = $this->page404;

        if (empty($path))
        {
            return $this->pageDefault;
        }

        $path = (array)$path;

        array_walk(
            $path,
            function (&$str)
            {
                $str = ucfirst(strtolower($str));
            }
        );

        $lastElement = end($path);
        $lastElementActonName = $this->getActionFunctionName($lastElement);

        unset($path[count($path) - 1]);

        #$controller = implode('', $path) . 'Controller';
        $controller = $this->getControllerClassName($path);
        if (class_exists($controller))
        {
            if (is_callable([$controller, $lastElementActonName], true))
            {
                return ['controller' => $controller, 'action' => $lastElementActonName];
            }
        }

        // Find if $lastElement is a part of the controller name, not an action.
        $path[] = $lastElement;
        #$controller = implode('', $path) . 'Controller';
        $controller = $this->getControllerClassName($path);
        if (class_exists($controller))
        {
            return ['controller' => $controller, 'action' => $this->pageDefault['action']];
        }

        // Find if this is the default controller.
        $path[count($path) - 1] = $this->pageDefault['controller'];
        #$controller = implode('', $path) . 'Controller';
        $controller = $this->getControllerClassName($path);
        if (class_exists($controller))
        {
            $action = is_callable(
                [$controller, $lastElementActonName],
                true
            ) ? $lastElementActonName : $this->getActionFunctionName($this->pageDefault['action']);
            return ['controller' => $controller, 'action' => $action];
        }

        return $ret;
    }

    /**
     * Создать название вызываемой функции по названию акции.
     *
     * @param string $urlActionName название акции в URL
     * @return string название функции акции
     */
    protected function getActionFunctionName($urlActionName)
    {
        $urlActionName = !empty($urlActionName) ?: $this->pageDefault['action'];
        $action = 'action' . ucfirst(strtolower($urlActionName));
        return $action;
    }

    /**
     * Create controller name.
     * Проанализировать путь и найти по нему контроллер и действие.
     * Если контроллер или действие не найдено, возвращает контроллер и действие к странице 404.
     *
     * @param array $urlControllerParts путь к контроллеру в URL
     * @return string название класса контроллера
     */
    protected function getControllerClassName($urlControllerParts)
    {
        $urlControllerParts = !empty($urlControllerParts) ?: $this->pageDefault['controller'];
        $urlControllerParts = (array)$urlControllerParts;

        array_walk(
            $urlControllerParts,
            function (&$str)
            {
                $str = ucfirst(strtolower($str));
            }
        );

        $controller = implode('', $urlControllerParts) . 'Controller';
        return $controller;
    }


    /**
     * Analyse URL then evoke controller action.
     */
    public function run()
    {
        $controllerName = $this->getControllerName($this->pageDefault['controller']);
        $actionName = $this->getActionName($this->pageDefault['action']);

        if (isset($_REQUEST['route']))
        {
            $route = trim($_REQUEST['route'], '/\\');
            $this->parts = array_filter(explode('/', $route));

            $this->getControllerActionName($this->parts);
        }

        if (!is_readable(APP_DIR . "controllers/$controllerName.php"))
        {
            // No such page.
            $controllerName = $this->getControllerName($this->page404['controller']);
            $actionName = $this->getActionName($this->page404['action']);
        }
        elseif (!is_callable(array($controllerName, ucfirst($actionName))))
        {
            $controllerName = $this->getControllerName($this->page404['controller']);
            $actionName = $this->getActionName($this->page404['action']);

            #// No action in the controller - set default.
            #$actionName = $this->getActionName('index');
        }

        (new $controllerName($this->config))->$actionName($this->parts);
    }
}
