<?php


namespace CoreBundle\Core;


abstract class ServicesCore
{
    public $db;

    public function __construct()
    {
        $this->db = Data::getInstance();
    }
}