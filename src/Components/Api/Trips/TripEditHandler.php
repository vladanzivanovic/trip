<?php

namespace CoreBundle\Components\Api\Trips;


use CoreBundle\Core\ServicesCore;
use CoreBundle\Helper\SessionHelper;
use CoreBundle\Model\TripDataModel;
use CoreBundle\Model\TripModel;

class TripEditHandler extends ServicesCore
{
    private $sessionHelper;

    /**
     * TripEditHandler constructor.
     *
     * @param SessionHelper $sessionHelper
     */
    public function __construct(
        SessionHelper $sessionHelper
    ) {
        parent::__construct();
        $this->sessionHelper = $sessionHelper;
    }

    /**
     * @param array $data
     *
     * @return int
     */
    public function insertTrip(array $data): int
    {
        $user = $this->sessionHelper->getUser();

        $query = $this->db->getQueryBuilder()
            ->createQuery(TripModel::TABLE)
            ->insert([
                'name' => ':name',
                'type' => ':type',
                'user_id' => ':user',
            ])
            ->setParameters([
                $data['name'],
                $data['type'],
                $user['id']
            ]);

        $tripId = $this->db->write($query->getSql());

        return (int) $tripId;
    }

    /**
     * @param array $data
     */
    public function addTripData(array $data): void
    {
        foreach ($data as $gpx) {
            $query = $this->db->getQueryBuilder()
                ->createQuery(TripDataModel::TABLE)
                ->insert([
                    'latitude' => ':lat',
                    'longitude' => ':lng',
                    'trip_id' => ':tripId',
                    'ele' => ':ele'
                ])
                ->setParameters([
                    $gpx['lat'],
                    $gpx['lng'],
                    $gpx['tripId'],
                    $gpx['ele'],
                ]);

            $this->db->write($query->getSql());
        }
    }

    /**
     * @param int $tripId
     */
    public function removeTrip(int $tripId)
    {
        $queryTripData = $this->db->getQueryBuilder()
            ->createQuery(TripDataModel::TABLE)
            ->delete()
            ->setWhere('trip_id = :trip')
            ->setParameters(['trip' => $tripId]);

        $this->db->remove($queryTripData->getSql());

        $queryTrip = $this->db->getQueryBuilder()
            ->createQuery(TripModel::TABLE)
            ->delete()
            ->setWhere('id = :id')
            ->setParameters(['id' => $tripId]);

        $this->db->remove($queryTrip->getSql());
    }
}