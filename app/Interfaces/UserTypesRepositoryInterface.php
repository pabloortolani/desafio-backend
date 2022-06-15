<?php

namespace App\Interfaces;

use App\Models\UserTypes;

interface UserTypesRepositoryInterface
{
    public function findByName(string $name): ?UserTypes;
}
