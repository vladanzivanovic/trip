<?php

namespace CoreBundle\Factory\Api\Trips;


use CoreBundle\Components\Pages\TripGetService;
use CoreBundle\Core\Container;
use CoreBundle\Lib\TestResponse;
use Symfony\Component\HttpFoundation\Request;

class TripGetFactory extends Container
{
    private static $instance = null;

    /**
     * @return TripGetFactory|null
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof TripGetFactory) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param Request $request
     *
     * @return TestResponse
     */
    public function getTripData(Request $request): TestResponse
    {
        $tripId = $request->query->getInt('trip_id');

        /** @var TripGetService $tripService */
        $tripService = $this->getServices('app.trip_get_service');
        $tripData = $tripService->getTripData($tripId);

        return new TestResponse(['route' => $tripData]);
    }
}