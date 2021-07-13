<?php

namespace App\Repositories;

abstract class CoreRepository
{

    protected $model;

    public function __construct() {
        if ($this->getClass()) {
            $this->model = app($this->getClass());
        }
    }

    abstract protected function getClass();

    protected function startQuery() {
        return clone $this->model;
    }

}
