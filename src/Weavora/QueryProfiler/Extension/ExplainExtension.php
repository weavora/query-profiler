<?php

namespace Weavora\QueryProfiler\Extension;

class ExplainExtension extends Extension
{
    public function getProfile()
    {
        $tables = $this->getConnection()->fetchAll("EXPLAIN " . $this->getQuery());

        $profile = array();
        foreach($tables as $table) {
            $profile[] = array(
                'id' => $table['id'],
                'select_type' => $table['select_type'],
                'table' => $table['table'],
                'type' => $table['type'],
                'possible_keys' => $table['possible_keys'],
                'key' => $table['key'],
                'key_len' => $table['key_len'],
                'ref' => $table['ref'],
                'rows' => $table['rows'],
                'extra' => $table['Extra'],

                'brief' => "#{$table['id']} {$table['select_type']} {$table['table']} {$table['type']}"
                        . (!empty($table['key']) ? " ({$table['key']}[{$table['key_len']}])" : "")
                        . (!empty($table['Extra']) ? " // {$table['Extra']}" : "")
            );

        }

        return $profile;
    }

}
