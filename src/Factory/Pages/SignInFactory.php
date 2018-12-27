<?php

namespace CoreBundle\Factory\Pages;

use CoreBundle\Core\Container;

class SignInFactory extends Container
{
    private static $instance = null;

    /**
     * @return SignInFactory|null
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof SignInFactory) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    public function index(): void
    {
        $title = 'Welcome';
        require_once(__DIR__ . "/../../Resources/Views/header.php");
        require_once(PAGES_PATH."/sign-in.php");
        require_once(__DIR__ . "/../../Resources/Views/footer.php");
    }
}