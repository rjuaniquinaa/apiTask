<?php

namespace App\Repositories;

abstract class BaseRepo
{
    public $model;
    public $queryBuilder;

    public function __construct()
    {
        $this->model = $this->getModel();
        $this->queryBuilder = $this->model->query();
    }

    abstract public function getModel();

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $fields = array())
    {
        return $this->model->create($fields);
    }

    public function newInstance(array $data = array())
    {
        return $this->model->newInstance($data);
    }

    public function delete($model)
    {
        if (is_numeric($model) || ctype_alnum($model)) {
            return $this->findOrFail($model)->delete();
        }
    }

    public function findOrFail($id, $columns = array('*'))
    {
        return $this->model->findOrFail($id, $columns);
    }

    public function show($id)
    {
        return $this->findOrFail($id);
    }

    public function all()
    {
        return $this->model->all();
    }
}