<?php

namespace CoreBundle\Model;

use CoreBundle\Core\ModelCore;
use CoreBundle\Entities\User;

class UserModel extends ModelCore
{
    const TABLE = 'users';
    /**
     * @param string $email
     *
     * @return array|bool|null
     */
    public function getUser(string $email)
    {
        $query = $this->db->getQueryBuilder()
            ->createQuery(self::TABLE, 'user')
            ->select([
                'user.id',
                'user.email',
                'user.password'
            ])
            ->setWhere('user.email = :email')
            ->setParameters([':email' => $email]);

        return $this->db->oneOrNullResult($query->getSql());
    }
}