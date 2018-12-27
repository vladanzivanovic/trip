<?php

namespace CoreBundle\Lib;

class Bcrypt
{
    /**
     * @param     $password
     * @param int $cost
     *
     * @return bool|string
     */
    public function encode($password, $cost = 4)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
    }

    /**
     * @param $password
     * @param $hash
     *
     * @return bool
     */
    public function is_valid($password, $hash)
    {
        return password_verify($password, $hash);
    }
}