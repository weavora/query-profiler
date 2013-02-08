<?php

namespace Weavora\QueryProfiler;

class Profiler
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * Profiler extensions
     * @var Extension\Extension[]
     */
    private $extensions;

    /**
     *
     */
    public function __construct()
    {
        $this->addExtension('execution_time', new Extension\ExecutionTimeExtension());
        $this->addExtension('profile', new Extension\ProfileExtension());
        $this->addExtension('status', new Extension\StatusExtension());
        $this->addExtension('explain', new Extension\ExplainExtension());
    }

    /**
     * @param string $query
     * @return Profile
     */
    public function profile($query)
    {
        foreach($this->getReversedExtensions() as $extension) {
            $extension->setConnection($this->getConnection());
            $extension->setQuery($query);
            $extension->beforeExecute();
        }

        $this->getConnection()->execute($query);

        foreach($this->getExtensions() as $extension) {
            $extension->afterExecute();
        }

        $profile = new Profile();
        $profile->query = $query;
        foreach($this->getExtensions() as $name => $extension) {
            $profile->profiles[$name] = $extension->getProfile();
        }
        return $profile;
    }

    /**
     * @param Extension\Extension[] $extensions
     */
    public function setExtensions(array $extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * @return Extension\Extension[]
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * @return Extension\Extension[]
     */
    protected function getReversedExtensions()
    {
        return array_reverse($this->extensions);
    }

    /**
     * @param $name
     * @param Extension\Extension $extension
     */
    public function addExtension($name, Extension\Extension $extension)
    {
        $this->extensions[$name] = $extension;
    }

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
}
