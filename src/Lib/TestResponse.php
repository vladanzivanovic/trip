<?php

namespace CoreBundle\Lib;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TestResponse extends JsonResponse
{
    public function __construct($data = null, $status = 200, array $headers = array(), $json = false)
    {
        parent::__construct($data, $status, $headers, $json);

        return $this->output(Request::createFromGlobals());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function output(Request $request)
    {
        $this->prepare($request);

        return parent::send();
    }
}