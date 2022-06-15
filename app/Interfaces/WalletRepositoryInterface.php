<?php

namespace App\Interfaces;

use App\Models\Wallet;

interface WalletRepositoryInterface
{
    public function create(int $userId): Wallet;
    public function find(int $id): ?Wallet;
}
