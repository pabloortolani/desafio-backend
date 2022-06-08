<?php

namespace App\Repository;

use App\Models\UserTypes;

class UserTypesRepository
{
    private UserTypes $model;

    public function __construct(UserTypes $model)
    {
        $this->model = $model;
    }

    public function findByName(string $name):? UserTypes
    {
        return $this->model->where('name', $name)->first();
    }
}
