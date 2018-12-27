<?php

namespace CoreBundle\Factory\Pages;

use CoreBundle\Core\Container;

class SignUpFactory extends Container
{
    private static $instance = null;

    /**
     * @return SignUpFactory|null
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof SignUpFactory) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    public function index(): void
    {
        $title = 'Sign Up';
        require_once(VIEWS_PATH."/header.php");
        require_once(PAGES_PATH."/sign-up.php");
        require_once(VIEWS_PATH."/footer.php");
    }
}