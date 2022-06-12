<?php

namespace App\Repository;

use App\Models\UserTypes;

class UserTypesRepository
{
    public function __construct(private UserTypes $model) {}

    public function findByName(string $name): ?UserTypes
    {
        return $this->model->where('name', $name)->first();
    }
}
