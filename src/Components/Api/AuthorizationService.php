<?php

namespace CoreBundle\Components\Api;

use CoreBundle\Helper\SessionHelper;
use CoreBundle\Lib\Bcrypt;
use CoreBundle\Model\UserModel;

class AuthorizationService
{
    private $userModel;
    private $bcrypt;
    private $sessionHelper;

    /**
     * AuthorizationService constructor.
     *
     * @param UserModel     $userModel
     * @param Bcrypt        $bcrypt
     * @param SessionHelper $sessionHelper
     */
    public function __construct(
        UserModel $userModel,
        Bcrypt $bcrypt,
        SessionHelper $sessionHelper
    ) {
        $this->userModel = $userModel;
        $this->bcrypt = $bcrypt;
        $this->sessionHelper = $sessionHelper;
    }

    /**
     * @param $email
     * @param $password
     *
     * @return bool
     */
    public function authorizeUser($email, $password)
    {
        $user = $this->userModel->getUser($email);

        if ($this->bcrypt->is_valid($password, $user['password'])) {
            $this->sessionHelper->setUser($user);
            return true;
        }

        return false;
    }
}