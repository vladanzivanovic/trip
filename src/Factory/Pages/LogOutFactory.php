<?php

namespace CoreBundle\Factory\Pages;


use CoreBundle\Core\Container;
use CoreBundle\Helper\SessionHelper;
use CoreBundle\Lib\TestRouter;

class LogOutFactory extends Container
{
    private static $instance = null;

    /**
     * @return LogOutFactory|null
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof LogOutFactory) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function index()
    {
        $this->getSession()->unset('user');

        header('Location: '.$this->getRouter()->generate('login'));
    }
}