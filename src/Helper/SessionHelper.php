<?php

namespace CoreBundle\Helper;

class SessionHelper
{
    private $sessionData = null;

    public function __construct()
    {
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }

        $this->sessionData = $_SESSION;
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return session_id();
    }

    /**
     * @return null|array
     */
    public function getSessionBag()
    {
        return $this->sessionData;
    }

    /**
     * @param $key
     *
     * @return null
     */
    public function get($key)
    {
        if($this->has($key)) {
            return $this->sessionData[$key];
        }
        return null;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($this->sessionData[$key]);
    }

    /**
     * @param $key
     * @param $data
     */
    public function set($key, $data)
    {
        $this->sessionData[$key] = $data;

        $_SESSION[$key] = $data;
    }

    /**
     * @param $key
     */
    public function unset($key)
    {
        if (true === $this->has($key)) {
            unset($this->sessionData[$key]);
            unset($_SESSION[$key]);
        }
    }

    /**
     * @param $data
     */
    public function setUser($data)
    {
        $this->set('user', $data);
    }

    /**
     * @return array|null
     */
    public function getUser()
    {
        return $this->get('user');
    }
}