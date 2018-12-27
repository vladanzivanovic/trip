<?php

namespace CoreBundle\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface Parser
{
    public function parse(Request $request);
}