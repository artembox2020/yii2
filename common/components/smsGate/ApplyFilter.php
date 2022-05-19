<?php

namespace SmsGate\Management;

class ApplyFilter
{
    public $query;

    public function __construct(array $conditions)
    {
        $query = '';
        foreach ($conditions as $operator => $operatorConditions) {
            foreach ($operatorConditions as $condition) {
                $query .= $operator. ' '. $condition. ' ';
            }
        }

        $this->query = $query;
    }
}
