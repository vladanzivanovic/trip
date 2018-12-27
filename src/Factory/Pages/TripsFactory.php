<?php

namespace CoreBundle\Factory\Pages;

use CoreBundle\Components\Pages\TripGetService;
use CoreBundle\Core\Container;

class TripsFactory extends Container
{
    private static $instance = null;

    /**
     * @return TripsFactory|null
     * @throws \Exception
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof TripsFactory) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    public function index(): void
    {
        $title = 'Trip List';
        /** @var TripGetService $tripService */
        $tripService = $this->getServices('app.trip_get_service');
        $trips = $tripService->getTrips();
        $tripTypes = $this->getContainer()->getParameter('type');
        $route = $this->getRouter();

        require_once(VIEWS_PATH."/header.php");
        require_once(PAGES_PATH . "/trips.php");
        require_once(VIEWS_PATH."/footer.php");
    }
}