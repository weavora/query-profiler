<?php

namespace Weavora\QueryProfiler\Extension;

use Weavora\QueryProfiler\Connection;

abstract class Extension
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $query;

    public function beforeExecute()
    {

    }

    public function afterExecute()
    {

    }

    abstract public function getProfile();

    /**
     * @param \Weavora\QueryProfiler\Connection $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return \Weavora\QueryProfiler\Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param string $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }
}
