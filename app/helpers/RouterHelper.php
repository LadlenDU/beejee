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
     * Проанализировать путь и найти по нему названия класса контроллера и название функции действие.
     * Если контроллер или действие не найдено, возвращает контроллер и действие к странице 404.
     *
     * @param array $path строка пути, разделенная в массив
     * @return array
     */
    protected function getRoute($path)
    {
        $ret = $this->getControllerActionName($this->page404);

        if (empty($path))
        {
            return $this->getControllerActionName($this->pageDefault);
        }

        $path = (array)$path;

        array_walk(
            $path,
            function (&$str)
            {
                $str = ucfirst(strtolower($str));
            }
        );

        if (($map = $this->ifControllerClassAndActionNameExist($path)) ||
            ($map = $this->ifControllerClassAndDefaultActionNameExist($path)) ||
            ($map = $this->ifDefaultControllerClassAndActionNameExist($path)) ||
            ($map = $this->ifDefaultControllerClassAndDefaultActionNameExist($path))
        )
        {
            $ret = $map;
        }

        return $ret;
    }

    /**
     * Проверка существования указанного класса контроллера и указанной функции акции.
     *
     * @param array $path путь URL разбитый в массив
     * @return array|bool PHP название контроллера и акции или false если соответствие не найдено
     */
    protected function ifControllerClassAndActionNameExist($path)
    {
        $ret = false;

        $actionNameURL = end($path);
        $controllerPathURL = $path;
        unset($controllerPathURL[count($controllerPathURL) - 1]);

        $controllerClass = $this->getControllerClassName($controllerPathURL);
        if (class_exists($controllerClass))
        {
            $actionName = $this->getActionFunctionName($actionNameURL);
            if (is_callable([$controllerClass, $actionName]))
            {
                $ret = ['controller' => $controllerClass, 'action' => $actionName];
            }
        }

        return $ret;
    }

    /**
     * Проверка существования указанного класса контроллера и функции акции по умолчанию.
     *
     * @param $path путь URL разбитый в массив
     * @return array|bool PHP название контроллера и акции или false если соответствие не найдено
     */
    protected function ifControllerClassAndDefaultActionNameExist($path)
    {
        $ret = false;

        $controllerClass = $this->getControllerClassName($path);
        if (class_exists($controllerClass))
        {
            $actionName = $this->getActionFunctionName($this->pageDefault['action']);
            if (is_callable([$controllerClass, $actionName]))
            {
                $ret = ['controller' => $controllerClass, 'action' => $actionName];
            }
        }

        return $ret;
    }

    /**
     * Проверка существования класса контроллера по умолчанию и указанной функции акции.
     *
     * @param $path путь URL разбитый в массив
     * @return array|bool PHP название контроллера и акции или false если соответствие не найдено
     */
    protected function ifDefaultControllerClassAndActionNameExist($path)
    {
        $ret = false;

        $controllerPathURL = $path;
        $controllerPathURL[count($controllerPathURL) - 1] = $this->pageDefault['controller'];

        $controllerClass = $this->getControllerClassName($controllerPathURL);
        if (class_exists($controllerClass))
        {
            $actionName = $this->getActionFunctionName(end($path));
            if (is_callable([$controllerClass, $actionName]))
            {
                return ['controller' => $controllerClass, 'action' => $actionName];
            }
        }

        return $ret;
    }

    /**
     * Проверка существования класса контроллера по умолчанию и функции акции по умолчанию (для URL типа /user, /admin).
     *
     * @param $path путь URL разбитый в массив
     * @return array|bool PHP название контроллера и акции или false если соответствие не найдено
     */
    protected function ifDefaultControllerClassAndDefaultActionNameExist($path)
    {
        $ret = false;

        $controllerPathURL = $path;
        $controllerPathURL[] = $this->pageDefault['controller'];

        $controllerClass = $this->getControllerClassName($controllerPathURL);
        if (class_exists($controllerClass))
        {
            $actionName = $this->getActionFunctionName($this->pageDefault['action']);
            if (is_callable([$controllerClass, $actionName]))
            {
                return ['controller' => $controllerClass, 'action' => $actionName];
            }
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
        $urlActionName = $urlActionName ?: $this->pageDefault['action'];
        $action = 'action' . ucfirst(strtolower($urlActionName));
        return $action;
    }

    /**
     * Создать название класса контроллера по пути к нему.
     *
     * @param array $urlControllerParts путь к контроллеру в URL, разбитый на массив
     * @return string название класса контроллера
     */
    protected function getControllerClassName($urlControllerParts)
    {
        $urlControllerParts = $urlControllerParts ?: $this->pageDefault['controller'];
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
     * Создает из URL параметров контроллера и действия их PHP названия.
     *
     * @param array $params содержит параметры URL контроллера и действия
     * @return array
     */
    protected function getControllerActionName($params)
    {
        return [
            'controller' => $this->getControllerClassName($params['controller']),
            'action' => $this->getActionFunctionName($params['action'])
        ];
    }

    /**
     * Проанализировать URL, после чего запустить действие.
     */
    public function run()
    {
        $controllerAction = $this->getControllerActionName($this->pageDefault);

        if (isset($_REQUEST['route']))
        {
            $route = trim($_REQUEST['route'], '/\\');
            $routeParts = array_filter(explode('/', $route));
            $controllerAction = $this->getRoute($routeParts);
        }

        (new $controllerAction['controller']())->$controllerAction['action']();
    }
}
