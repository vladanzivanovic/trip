<?php

namespace CoreBundle\Model;


use CoreBundle\Core\ModelCore;

class TripModel extends ModelCore
{
    const TABLE = 'trip';

    /**
     * @param int $userId
     *
     * @return array|bool|null
     */
    public function getTripsByUser(int $userId)
    {
        $query = $this->db->getQueryBuilder()
            ->createQuery(self::TABLE)
            ->select()
            ->setWhere('user_id = :user')
            ->setParameters(['user' => $userId]);

        return $this->db->execute($query->getSql());
    }
}