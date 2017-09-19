<?php

namespace App;

use App\Exceptions\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class BaseMongoModel extends Eloquent
{
    /**
     * The rules to be applied to the data.
     *
     * @var array
     */
    public static $rules = [];

    /**
     * The array of custom error messages.
     *
     * @var array
     */
    public static $customMessages = [];

    protected $connection = 'mongodb';

    public $appends = [
        'id',
    ];

    public $hidden = [
        '_id'
    ];

    public function save(array $options = array(),
                         array $rules = array(),
                         array $customMessages = array()
    ) {
        // If attribute of date type won't changed, convert to string date.
        foreach ($this->dates as $date) {
            if ( ! $this->isDirty($date)) {
                $this->attributes[$date] = $this->asDateTime($this->attributes[$date])->toDateString();
            }
        }

        $this->validate($rules, $customMessages);
        // Convert to UTCDateTime before of save the model
        foreach ($this->dates as $date) {
            $this->attributes[$date] = $this->fromDateTime($this->attributes[$date]);
        }

        try {
            return parent::save($options);
        } catch (\Exception $e) {
            Log::info('Error saving data: ' . $e->getMessage());
            throw new \Exception('An internal error occurred during saving data.');
        }
    }

    public function validate(array $rules = array(), array $customMessages = array())
    {
        $rules = empty($rules) ? static::$rules : $rules;
        if ( ! empty($rules)) {
            $customMessages = empty($customMessages)? static::$customMessages : $customMessages;
            $data = array_only($this->getAttributes(), array_keys($rules));
            $validation = Validator::make($data, $rules, $customMessages);

            if ($validation->fails()) {
                throw new ValidationException( (array) $validation->messages()->toArray());
            }
        }
    }

    public function getIdAttribute($value = NULL)
    {
        if (isset($this->attributes['id'])) {
            return is_numeric($this->attributes['id'])? (int)$this->attributes['id'] :$this->attributes['id'];
        } else if (isset($this->attributes['_id'])) {
            return parent::getIdAttribute($value);
        }
    }

    public function setIdAttribute($value)
    {
        $this->attributes['id'] = $value;

        return $this;
    }
}
