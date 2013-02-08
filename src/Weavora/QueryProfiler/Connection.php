<?php

namespace Weavora\QueryProfiler;

class Connection
{

    /**
     * MySQL host
     * @var string
     */
    private $host = 'localhost';

    /**
     * Database name
     * @var string
     */
    private $database = 'test';

    /**
     * User
     * @var string
     */
    private $user;

    /**
     * Password
     * @var
     */
    private $password;

    /**
     * @var \PDO
     */
    private $pdo;

    public function __construct($host = 'localhost', $database = 'test', $user = null, $password = null)
    {
        $this->setHost($host);
        $this->setDatabase($database);
        $this->setUser($user);
        $this->setPassword($password);
    }

    public function connect()
    {
        $this->pdo = new \PDO($this->getDSN(), $this->getUser(), $this->getPassword());
    }

    /**
     * @param string $query
     * @param array $params
     * @return \PDOStatement
     */
    private function executeQuery($query, $params = array())
    {
        $statement = $this->pdo->prepare($query);
        if (!$statement->execute($params)) {
            $errorInfo = $statement->errorInfo();
            throw new \PDOException("ERROR[{$errorInfo[1]}] {$errorInfo[2]}", $errorInfo[1]);
        }
        return $statement;
    }

    public function execute($query, $params = array())
    {
        return $this->executeQuery($query, $params)->rowCount();
    }

    public function fetchAll($query, $params = array())
    {
        return $this->executeQuery($query, $params)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetch($query, $params = array())
    {
        return $this->executeQuery($query, $params)->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchColumn($query, $columnNumber = 0, $params = array())
    {
        return $this->executeQuery($query, $params)->fetchColumn($columnNumber);
    }

    /**
     * @param string $database
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param  $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getDSN()
    {
        return "mysql:host={$this->getHost()};dbname={$this->getDatabase()}";
    }
}
