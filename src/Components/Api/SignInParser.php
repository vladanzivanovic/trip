<?php

namespace CoreBundle\Components\Api;


use CoreBundle\Interfaces\Parser;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class SignInParser implements Parser
{
    public function parse(Request $request)
    {
        $parameterBag = $request->request;

        return [
            'email' => $parameterBag->get('email'),
            'password' => $parameterBag->get('password'),
        ];
    }
}