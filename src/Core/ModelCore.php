<?php


namespace CoreBundle\Core;

abstract class ModelCore
{
    public $db;

    public function __construct()
    {
        $this->db = Data::getInstance();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        $query = "SELECT * FROM ". static::TABLE_NAME ." WHERE id = {$id}";

        return $this->db->oneOrNullResult($query);
    }

    /**
     * @return mixed
     */
    public function findAll()
    {
        $query = "SELECT * FROM ". static::TABLE_NAME ;

        return $this->db->query($query);
    }

    /**
     * @param array $params
     *
     * @return mixed
     */
    public function findByParams(array $params)
    {
        $where = [];

        foreach ($params as $prop => $value) {
            $where[] = "$prop = '$value'";
        }

        $query = "SELECT * FROM ". static::TABLE_NAME ." WHERE ". implode(' AND ', $where);

        return $this->db->query($query);
    }

    /**
     * @param array $params
     *
     * @return mixed
     */
    public function findOneByParams(array $params)
    {
        $where = [];

        foreach ($params as $prop => $value) {
            $where[] = "$prop = '$value'";
        }

        $query = "SELECT * FROM ". static::TABLE_NAME ." WHERE ". implode(' AND ', $where);

        return $this->db->oneOrNullResult($query);
    }
}