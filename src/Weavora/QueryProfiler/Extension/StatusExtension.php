<?php

namespace Weavora\QueryProfiler\Extension;

class StatusExtension extends Extension
{

    private $status = array();

    private $validVariables = array(
        'Created_tmp_',
        'Handler_',
        'Key_read',
        'Key_write',
        'Select_',
        'Sort_',
    );

    public function beforeExecute()
    {
        $this->getConnection()->execute("FLUSH STATUS");
    }

    public function afterExecute()
    {
        $this->status = $this->getConnection()->fetchAll("SHOW STATUS");
    }

    public function getProfile()
    {
        $profile = array();
        foreach($this->status as $variable) {
            if ($this->isValidVariable($variable['Variable_name'])) {
                $profile[strtolower($variable['Variable_name'])] = $variable['Value'];
            }

        }
        return $profile;
    }

    protected function isValidVariable($variableName)
    {
        foreach($this->validVariables as $prefix) {
            if (strpos($variableName, $prefix) === 0) {
                return true;
            }
        }
        return false;
    }

}
