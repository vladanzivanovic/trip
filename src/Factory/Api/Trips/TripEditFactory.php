<?php

namespace CoreBundle\Factory\Api\Trips;

use CoreBundle\Components\Api\Trips\TripEditHandler;
use CoreBundle\Components\Api\Trips\TripGpxParser;
use CoreBundle\Components\Api\Trips\TripsParser;
use CoreBundle\Core\Container;
use CoreBundle\Lib\TestResponse;
use CoreBundle\Validators\TripValidator;
use Symfony\Component\HttpFoundation\Request;

class TripEditFactory extends Container
{
    private static $instance = null;

    /** @var TripsParser $parser */
    private $parser;
    /** @var TripEditHandler $handler */
    private $handler;

    /**
     * TripEditFactory constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->handler = $this->getServices('app.trip_edit_handler');
        $this->parser = $this->getServices('app.trips_parser');
    }

    /**
     * @return TripEditFactory|null
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof TripEditFactory) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function addTrip(Request $request): TestResponse
    {
        $data = $this->parser->parse($request);

        /** @var TripValidator $validator */
        $validator = $this->getServices('app.trip_validator');
        $validator->setValidationRules();
        $errors = $validator->validate($data);

        if (null != $errors) {
            return new TestResponse(['errors' => $errors], TestResponse::HTTP_BAD_REQUEST);
        }

        $tripId = $this->handler->insertTrip($data);

        /** @var TripGpxParser $gpxParser */
        $gpxParser = $this->getServices('app.trips_gpx_parser');
        $gpxParsed = $gpxParser->parseGpx($data['gpx'], $tripId);

        $this->handler->addTripData($gpxParsed);

        return new TestResponse(['id' => $tripId]);
    }

    /**
     * @param Request $request
     *
     * @return TestResponse
     */
    public function deleteTrip(Request $request): TestResponse
    {
        $id = $request->request->getInt('id');

        $this->handler->removeTrip($id);

        return new TestResponse();
    }
}