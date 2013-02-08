<?php

namespace Weavora\QueryProfiler\Extension;

class ExecutionTimeExtension extends Extension
{
    /**
     * Execution start time
     * @var float
     */
    private $startTime = 0.0;

    /**
     * Execution end time
     * @var float
     */
    private $endTime = 0.0;

    /**
     * {@inheritDoc}
     */
    public function beforeExecute()
    {
        $this->startTime = microtime(true);
    }

    /**
     * {@inheritDoc}
     */
    public function afterExecute()
    {
        $this->endTime = microtime(true);
    }

    /**
     * {@inheritDoc}
     */
    public function getProfile()
    {
        return ($this->endTime - $this->startTime);
    }

}
