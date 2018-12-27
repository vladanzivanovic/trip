<?php

namespace CoreBundle\Interfaces;

interface ValidationInterface
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function validate(array $data);
}