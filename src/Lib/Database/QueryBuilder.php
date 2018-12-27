<?php

namespace CoreBundle\Lib\Database;

class QueryBuilder
{
    const TYPE_INSERT = 1;
    const TYPE_UPDATE = 2;
    const TYPE_DELETE = 3;
    const TYPE_SELECT = 4;

    private $table = null;
    private $tableAlias = null;
    private $select = ['*'];
    private $fields = [];
    private $where = null;
    private $join = [];
    private $type = null;
    private $order = null;
    private $limit = null;
    private $params = [];

    /**
     * @param $tableName
     * @param $alias
     *
     * @return $this
     */
    public function createQuery($tableName, $alias = null)
    {
        $this->table = $tableName;
        $this->tableAlias = $alias;

        return $this;
    }

    public function insert(array $data)
    {
        $this->fields['keys'] = array_keys($data);
        $this->fields['values'] = array_values($data);

        $this->type = self::TYPE_INSERT;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function update(array $data)
    {
        $this->fields = [];

        foreach ($data as $field => $value) {
            $this->fields[] = " {$field} = '{$value}'";
        }

        $this->type = self::TYPE_UPDATE;

        return $this;
    }

    /**
     * @param array|null $select
     *
     * @return $this
     */
    public function select(array $select = ['*'])
    {
        $this->select = $select;

        $this->type = self::TYPE_SELECT;

        return $this;
    }

    /**
     * @return $this
     */
    public function delete()
    {
        $this->type = self::TYPE_DELETE;

        return $this;
    }

    /**
     * @param string $where
     *
     * @return QueryBuilder
     */
    public function setWhere(string $where)
    {
        $this->where = $where;

        return $this;
    }

    /**
     * @param array $params
     *
     * @return QueryBuilder
     */
    public function setParameters(array $params): QueryBuilder
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->params;
    }

    /**
     * @param $table
     * @param $alias
     * @param $condition
     *
     * @return $this
     */
    public function innerJoin($table, $alias, $condition)
    {
        $this->join[] = "INNER JOIN $table as $alias ON $condition";

        return $this;
    }

    /**
     * @param $table
     * @param $alias
     * @param $condition
     *
     * @return $this
     */
    public function leftJoin($table, $alias, $condition)
    {
        $this->join[] = "LEFT JOIN $table as $alias ON $condition";

        return $this;
    }

    /**
     * @param $table
     * @param $alias
     * @param $condition
     *
     * @return $this
     */
    public function rightJoin($table, $alias, $condition)
    {
        $this->join[] = "RIGHT JOIN $table as $alias ON $condition";

        return $this;
    }

    /**
     * @param $order
     *
     * @return $this
     */
    public function orderBy($order)
    {
        $this->order = $order;

        return $this;
    }

    public function setLimit($limit, $offset = null)
    {
        $this->limit = $limit;

        if (null !== $offset) {
            $this->limit = $offset.', '.$limit;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        $query = null;
        $generator = GenerateQuery::getInstance();

        switch ($this->type) {
            case self::TYPE_INSERT:
                $query = $generator->generateInsertQuery(get_object_vars($this));
                break;
            case self::TYPE_UPDATE:
                $query = $generator->generateUpdateQuery(get_object_vars($this));
                break;
            case self::TYPE_DELETE:
                $query = $generator->generateDeleteQuery(get_object_vars($this));
                break;
            case self::TYPE_SELECT:
                $query = $generator->generateSelectQuery(get_object_vars($this));
                break;
        }

        return $query;
    }
}