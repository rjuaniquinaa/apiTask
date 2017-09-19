<?php

namespace App\Observers;

use App\Task;
use Illuminate\Support\Facades\Cache;

class TaskObserver
{
    /**
     * Listen to the Task created event.
     *
     * @param  Task $task
     * @return void
     */
    public function created(Task $task)
    {
        Cache::tags('recovered_tasks')->flush();
    }

    /**
     * Listen to the Task deleted event.
     *
     * @param Task $task
     * @return void
     */
    public function deleted(Task $task)
    {
        Cache::tags('recovered_tasks')->flush();
    }

    /**
     * Listen to the Task deleted event.
     *
     * @param  Task $task
     * @return void
     */
    public function updated(Task $task)
    {
        Cache::tags('recovered_tasks')->flush();
    }
}