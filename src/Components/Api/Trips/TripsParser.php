<?php

namespace CoreBundle\Components\Api\Trips;

use CoreBundle\Interfaces\Parser;
use Symfony\Component\HttpFoundation\Request;

class TripsParser implements Parser
{
    public function parse(Request $request)
    {
        $data = $request->request;
        $files = $request->files;

        return [
            'name' => $data->get('name'),
            'type' => $data->get('type'),
            'gpx' => $files->get('gpx'),
        ];
    }
}