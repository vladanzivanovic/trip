<?php
namespace CoreBundle\Core;

use \PDO;
use CoreBundle\Exceptions\ConnectException;
use CoreBundle\Exceptions\QueryException;
use CoreBundle\Lib\Database\QueryBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


/**
 * Class Data
 */
class Data extends \PDO
{
    protected $connectors = null;

    protected $_inUse;
    
    protected $server;

    protected static $instance = null;

    protected $procedural_link = null;

//    public $counter = 0;

//    const DB_DT_FORMAT = 'Y-m-d H:i:s';
//
//    const DB_T_FORMAT = 'H:i:s';
//
//    const DB_D_FORMAT = 'Y-m-d';

    const HYDRATE_ASSOC = 'assoc';

    private $container;
    private $loader;
    /** @var QueryBuilder $queryBuilder */
    private $queryBuilder;

    /**
     * @param string $server
     *
     * @throws ConnectException
     */
    public function __construct($server="default")
    {
        $this->getParams($server);
        $database = $this->connectors['name'];
        $host = $this->connectors['host'];
        $username = $this->connectors['user'];
        $password = $this->connectors['password'];
        $this->server = $server;

        try {
            parent::__construct(
                'mysql:host=' . $host . ';dbname=' . $database . '',
                $username,
                $password,
                [PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $PDOException) {
            throw new ConnectException($PDOException->getMessage());
        }

        $this->queryBuilder = new QueryBuilder();
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * @param string $statement
     * @param string $hydrateMode
     *
     * @return array|bool|null
     */
    public function execute(string $statement, $hydrateMode = self::HYDRATE_ASSOC)
    {
        $prepared = parent::prepare($statement);

        foreach ($this->getQueryBuilder()->getParameters() as $param => $value) {
            $prepared->bindParam($param, $value);
        }

        return $this->setResult($prepared, $hydrateMode);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function write($query)
    {
        try {
            $prepared = parent::prepare($query);
            $prepared->execute($this->getQueryBuilder()->getParameters());

            return $this->lastInsertId();
        }catch (\Throwable $throwable) {
            var_dump($throwable->getMessage()); exit();
        }
    }

    /**
     * @param $query
     */
    public function remove($query)
    {
        $prepared = parent::prepare($query);
        $prepared->execute($this->getQueryBuilder()->getParameters());
    }

    /**
     * @param array|string|integer $data
     *
     * @return array|string|integer
     */
    public function clearData($data)
    {
        if (is_array($data)) {
            foreach ($data as &$item) {
                $item = $this->real_escape_string($item);
            }

            return $data;
        }

        $this->real_escape_string($data);

        return $data;
    }

    /**
     * Select exactly one row from Db
     * If there is no record, return null
     *
     * @param string $query
     *
     * @return array|null
     */
    public function oneOrNullResult(string $query)
    {
        /** @var \PDOStatement $sql */
        $sql = $this->execute($query, null);

        if($sql->rowCount() == 0)
            return null;
        if($sql->rowCount() > 1)
            throw new QueryException('There is more then one row in db.', 0, $query);

        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Returns instance of Data object, this object is singletone
     *
     * @param string $server
     *
     * @return Data
     *
     * @throws ConnectException
     */
    public static function getInstance($server = 'default')
    { 
        if (!isset($instance[$server]) or self::$instance[$server] == null) {
            self::$instance[$server] = new self($server);
        }
        return self::$instance[$server];
    }

    protected function __clone()
    {
        // Me not like clones! Me smash clones!
    }

    /**
     * @param \PDOStatement $sql
     * @param string|null   $hydrateMode
     *
     * @return array|bool|null|\PDOStatement
     */
    private function setResult(\PDOStatement $sql, ?string $hydrateMode)
    {
        if(null === $hydrateMode) {
            $sql->execute();
            return $sql;
        }

        $result = null;

        switch ($hydrateMode) {
            case self::HYDRATE_ASSOC:
                $sql->execute();
                $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
                break;
        }

        return $result;
    }

    private function getParams($server)
    {
        $this->container = new ContainerBuilder();
        $this->loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ .'/../Resources/Config'));

        $this->loader->load('config.yml');

        $params = $this->container->getParameter('db');

        $this->connectors = $params[$server];
    }
}
