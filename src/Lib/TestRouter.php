<?php

namespace CoreBundle\Lib;

use CoreBundle\Extensions\ParameterExtension;
use CoreBundle\Helper\SessionHelper;
use Symfony\Component\HttpFoundation\Request;

class TestRouter extends \AltoRouter
{
    /** @var ParameterExtension $parameters */
    private $parameters;
    /** @var SessionHelper $sessionHelper */
    private $sessionHelper;

    /**
     * TestRouter constructor.
     */
    public function __construct()
    {
        $this->parameters = new ParameterExtension();
        $this->sessionHelper = $this->parameters->getServices('app.session_helper');

        $routes = $this->parameters->getParameter('routes');

        parent::__construct($routes);
    }

    /**
     * @param null $requestUrl
     * @param null $requestMethod
     *
     * @return array|bool
     * @throws \Exception
     */
    public function match($requestUrl = null, $requestMethod = null)
    {

        $requestMethod = $this->getRequestMethod($requestMethod);

        $match = parent::match();

        if (is_array($match)) {
            $this->redirect($match);

            list( $factory, $method ) = $this->getMethod($match['target']);

            if ( is_callable(array($factory::getInstance(), $method)) ) {
                call_user_func_array(array($factory::getInstance(),$method), array($this->setRequest($requestMethod, $match['params'])));

                return true;
            }
        }

        return false;
    }

    /**
     * @param $target
     *
     * @return array
     */
    private function getMethod($target)
    {
        $splitted = explode('::', $target);
        $factory = $splitted[0];
        $method = $splitted[1];

        return [$factory, $method];
    }

    /**
     * @param $requestMethod
     *
     * @return string
     */
    private function getRequestMethod($requestMethod)
    {
        if($requestMethod === null) {
            $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        }

        return $requestMethod;
    }

    /**
     * @param $method
     * @param $params
     *
     * @return Request
     */
    private function setRequest($method, $params)
    {
        switch ($method) {
            case 'GET':
                $_GET = $params;
                break;
            case 'DELETE':
                $_POST = $params;
                break;
        }

        return Request::createFromGlobals();
    }

    /**
     * @param $match
     *
     * @return bool
     */
    private function checkIfUrlIsAllowed($match)
    {
        $access = $this->parameters->getParameter('security');

        $user = $this->sessionHelper->getUser();

        foreach ($access as $route) {
            $routeExist = $route[0] === $match['name'];
            if ((false === $routeExist && !empty($user)) || true === $routeExist)  {
                return true;
            }
        }

        return false;
    }

    private function redirect($match)
    {
        $user = $this->sessionHelper->getUser();
        header("HTTP/1.1 301 Moved Permanently");

        if (false === $this->checkIfUrlIsAllowed($match)) {
            header('Location: '. $this->generate('login'));

            exit();
        }

        if ($match['name'] === 'login' && !empty($user)) {
            header('Location: '. $this->generate('trips-list'));

            exit();
        }

        return false;
    }
}