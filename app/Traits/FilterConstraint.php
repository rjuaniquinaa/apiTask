<?php

namespace App\Traits;

Trait FilterConstraint
{
    protected function parseFilterOptions(array $filters)
    {
        // Merge default options and options passed to method
        $defaultOptions = [
            'where'     => [],
            'limit'     => 5,
            'page'      => 1,
        ];
        $options = array_merge($defaultOptions, $filters);

        $where = [];
        foreach ($options['where'] as $orCondition) {
            $andWhere = [];
            foreach ($orCondition as $andCondition) {
                if ( ! $this->isValidCondition($andCondition)) {
                    throw new \InvalidArgumentException("The condition you are filtering by is not valid.");
                }
                $andWhere[] = [$andCondition[0], strtolower($andCondition[1]), $andCondition[2]];
            }
            $where[][] = $andWhere;
        }

        $options['where'] = $where;
        $options['limit'] = (int) $options['limit'];
        $options['page'] = (int) $options['page'];

        return $options;
    }

    private function isValidCondition($condition)
    {
        $operators = array("=", "!=", "IN", "LIKE", ">", ">=", "<", "<=");

        if (empty($condition[0]) or !is_string($condition[0])) {
            return false;
        }
        if (empty($condition[1]) or !in_array($condition[1], $operators)) {
            return false;
        }
        if ( ! isset($condition[2])) {
            return false;
        }
        return true;
    }

}