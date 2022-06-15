<?php

namespace App\Interfaces;

use App\Models\Transfer;

interface TransferRepositoryInterface
{
    public function create(array $data): Transfer;
}
