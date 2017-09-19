<?php

namespace App;


use App\Observers\TaskObserver;
use Illuminate\Notifications\Notifiable;

class Task extends BaseMongoModel
{
    //use Notifiable;

    const UNCOMPLETED = false;

    const COMPLETED = true;

    protected $collection = 'tasks';

    protected $fillable = ['title', 'due_date', 'description', 'completed'];

    protected $attributes = [
        'completed' => self::UNCOMPLETED,
    ];

    public static $rules = array(
        'title' => 'required',
        'due_date' => 'required|date|date_format:Y-m-d',
        'completed' => 'boolean',
    );

    protected $dates = array(
        'due_date'
    );

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TaskObserver::class,
        'deleted' => TaskObserver::class,
        'updated' => TaskObserver::class,
    ];

    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = $value;
    }

    public function setCompletedAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['completed'] = ($value === 'true') ?: ($value === 'false' ? false : $value);
    }
}
