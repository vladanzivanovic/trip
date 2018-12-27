<?php

namespace CoreBundle\Components\Pages;

use CoreBundle\Helper\SessionHelper;
use CoreBundle\Model\TripDataModel;
use CoreBundle\Model\TripModel;

class TripGetService
{
    private $sessionHelper;
    private $tripModel;
    private $tripDataModel;

    /**
     * TripGetService constructor.
     *
     * @param SessionHelper $sessionHelper
     * @param TripModel     $tripModel
     * @param TripDataModel $tripDataModel
     */
    public function __construct(
        SessionHelper $sessionHelper,
        TripModel $tripModel,
        TripDataModel $tripDataModel
    ) {
        $this->sessionHelper = $sessionHelper;
        $this->tripModel = $tripModel;
        $this->tripDataModel = $tripDataModel;
    }

    /**
     * @return array
     */
    public function getTrips():array
    {
        $user = $this->sessionHelper->getUser();

        $trips = $this->tripModel->getTripsByUser($user['id']);

        return $trips;
    }

    /**
     * @param int $tripId
     *
     * @return array
     */
    public function getTripData(int $tripId): array
    {
        $data = $this->tripDataModel->getTripData($tripId);

        return $data;
    }
}