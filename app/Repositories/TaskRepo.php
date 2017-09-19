<?php

namespace App\Repositories;

use App\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class TaskRepo extends BaseRepo
{

    public function getModel()
    {
        return new Task;
    }

    public function all(
        array $where = [],
        $limit = 100,
        $page = 1
    ) {
        $query = $this->queryBuilder;

        $query->where(function($query) use ($where) {
            $conditionOperator = "and";
            foreach ($where as $index => $whereOrCondition) {
                if ($index > 0) {
                    $conditionOperator = "or";
                }
                foreach ($whereOrCondition as $whereAndCondition) {
                    foreach ($whereAndCondition as $condition) {
                        $condition[] = $conditionOperator;
                        if (in_array($condition[0], $this->model->getDates())) {
                            $condition[2] = Carbon::createFromFormat('Y-m-d', $condition[2]);
                        }
                        call_user_func_array([$query, 'where'], $condition);
                        $conditionOperator = "and";
                    }
                }
            }
        });

        $query->skip(($page -1) * $limit)->take($limit);

        $key = sha1(json_encode(request()->all()));

        $result = Cache::tags(['recovered_tasks'])->remember($key, 15, function () use ($query) {
            return $query->get(['*']) ? : [];
        });

        return $result;
    }
}