<?php

/**
 * Class RouterHelper
 *
 * Routing.
 */
class RouterHelper
{
    /**
     * URL parts for default page.
     * @var array
     */
    protected $pageDefault = ['controller' => 'default', 'action' => 'index'];

    /**
     * URL parts for sub default page.
     * @var array
     *
     * protected $subPageDefault = ['controller' => 'default', 'action' => 'index'];*/

    /**
     * URL parts for empty page.
     * @var array
     */
    protected $page404 = ['controller' => 'default', 'action' => '404'];

    /**
     * Url parts.
     * @var array|null
     */
    protected $parts;

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
     * Create controller name.
     *
     * @param string $controllerPart url part of the controller
     * @return string controller name
     *
     * protected function getControllerName($controllerPart)
     * {
     * $controller = $this->pageDefault['controller'];
     * if (!empty($controllerPart))
     * {
     * $controller = ucfirst($controllerPart) . 'Controller';
     * }
     * return $controller;
     * }*/

    protected function getControllerActionName($path)
    {
        $ret = $this->page404;

        $path = ['admin', 'defAUlt'];

        if (empty($path))
        {
            return $this->pageDefault;
        }

        $path = (array)$path;

        array_walk(
            $path,
            function (&$str)
            {
                $str = ucfirst($str);
            }
        );
        $lastElement = end($path);
        unset($path[count($path) - 1]);

        $controller = implode('', $path) . 'Controller';
        if (class_exists($controller))
        {
            if (method_exists($controller, $lastElement))
            {
                return ['controller' => $controller, 'action' => $lastElement];
            }
        }

        // Find if $lastElement is a part of the controller name, not an action.
        $path[] = $lastElement;
        $controller = implode('', $path) . 'Controller';
        if (class_exists($controller))
        {
            return ['controller' => $controller, 'action' => $this->pageDefault['action']];
        }

        // Find if this is the default controller.
        $path[count($path) - 1] = $this->pageDefault['controller'];
        $controller = implode('', $path) . 'Controller';
        if (class_exists($controller))
        {
            $action = method_exists($controller, $lastElement) ? $lastElement : $this->pageDefault['action'];
            return ['controller' => $controller, 'action' => $action];
        }

        return $ret;
    }

    /**
     * Create action name.
     *
     * @param string $actionName url part of the action
     * @return string action name
     */
    protected
    function getActionName(
        $actionName
    )
    {
        $action = $this->pageDefault['action'];
        if (!empty($actionName))
        {
            $action = 'action' . ucfirst($actionName);
        }
        return $action;
    }

    /**
     * Analyse URL then evoke controller action.
     */
    public
    function run()
    {
        $controllerName = $this->getControllerName($this->pageDefault['controller']);
        $actionName = $this->getActionName($this->pageDefault['action']);

        if (isset($_REQUEST['route']))
        {
            $_REQUEST['route'] = '//some///route/ss//';
            $route = trim($_REQUEST['route'], '/\\');
            $this->parts = array_filter(explode('/', $route));

            #$path = str_replace('\\', DIRECTORY_SEPARATOR, strtolower($class, 0, $file_position + 1));

            if (isset($this->parts[0]))
            {
                #$controllerName = $this->getControllerName($this->parts[0]);
                $controllerName = $this->getControllerName($this->parts);
            }
            if (isset($this->parts[1]))
            {
                $actionName = $this->getActionName($this->parts[1]);
            }
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
