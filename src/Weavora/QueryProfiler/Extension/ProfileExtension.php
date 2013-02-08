<?php

namespace Weavora\QueryProfiler\Extension;

class ProfileExtension extends Extension
{
    public function getProfile()
    {
        $this->getConnection()->execute("SET profiling = 1");
        $this->getConnection()->execute($this->getQuery());
        $queryExecutionProfile = $this->getConnection()->fetchAll("SHOW PROFILE");

        $profile = array();
        foreach($queryExecutionProfile as $step) {
            $profile[strtolower($step['Status'])] = $step['Duration'];
        }
        return $profile;
    }

}
