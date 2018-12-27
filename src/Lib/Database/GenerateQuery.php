<?php

namespace CoreBundle\Lib\Database;

class GenerateQuery
{
    private static $instance = null;
    private $table = null;
    private $tableAlias = null;
    private $select = null;
    private $fields = [];
    private $where = null;
    private $join = [];
    private $order = null;
    private $limit = null;

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function generateInsertQuery(array $data)
    {
        $this->setDataForQuery($data);

        $insertFields = "(". implode(', ', $this->fields['keys']) .")
            VALUES (". implode(', ', array_fill(0, count($this->fields['keys']), '?')) .") 
        ";

        $query = "INSERT INTO ". $this->table ." ". $insertFields;
//        var_dump($query, $data); exit();
        return $query;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function generateUpdateQuery(array $data)
    {
        $this->setDataForQuery($data);

        $fields = implode(', ', $this->fields);
        $query = "UPDATE ".$this->table ." SET $fields ";
        $query .= $this->setWhere($this->where);

        return $query;
    }

    /**
     * @param $data
     *
     * @return string
     */
    public function generateSelectQuery(array $data)
    {
        $this->setDataForQuery($data);

        $query = "SELECT ".implode(', ', $this->select) ." ". $this->setFrom();

        if (!empty($this->join)) {
            $query .= implode("\r\n", $this->join)." ";
        }

        $query .= $this->setWhere($this->where);
        $query .= $this->setOrder();
        $query .= $this->setLimit();

        return $query;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function generateDeleteQuery(array $data)
    {
        $this->setDataForQuery($data);

        $query = "DELETE ".$this->setFrom();
        $query .= $this->setWhere($this->where);

        return $query;
    }

    private function setFrom()
    {
        $from = " FROM $this->table ";

        if (null !== $this->tableAlias) {
            $from .= " AS $this->tableAlias ";
        }

        return $from;
    }

    private function setWhere($where)
    {
        $query = '';

        if(!empty($where)) {
            $query .= " WHERE ".$where;
        }

        return $query;
    }

    /**
     * @return string
     */
    private function setOrder()
    {
        $order = '';
        if (!empty($this->order)) {
            $order = ' ORDER BY '.$this->order .' ';
        }

        return $order;
    }

    private function setLimit()
    {
        $limit = '';

        if (null !== $this->limit) {
            $limit = ' LIMIT '.$this->limit;
        }

        return $limit;
    }

    private function setDataForQuery(array $data)
    {
        $this->table = $data['table'];
        $this->tableAlias = $data['tableAlias'];
        $this->select = $data['select'];
        $this->where = $data['where'];
        $this->join = $data['join'];
        $this->fields = $data['fields'];
        $this->order = $data['order'];
        $this->limit = $data['limit'];
    }
}