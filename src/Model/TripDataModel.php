<?php

namespace CoreBundle\Model;


use CoreBundle\Core\ModelCore;

class TripDataModel extends ModelCore
{
    const TABLE = 'trip_data';

    public function getTripData(int $tripId)
    {
        $query = $this->db->getQueryBuilder()
            ->createQuery(self::TABLE)
            ->select([
                'latitude AS lat',
                'longitude AS lng'
            ])
            ->setWhere('trip_id = :tripId')
            ->setParameters(['tripId' => $tripId]);

        return $this->db->execute($query->getSql());
    }
}