<?php

namespace App\Repository;

use App\Interfaces\WalletRepositoryInterface;
use App\Models\Wallet;

class WalletRepository implements WalletRepositoryInterface
{
    public function __construct(private Wallet $model) {}

    public function create(int $userId): Wallet
    {
        return $this->model->create([
            'user_id' => $userId
        ]);
    }

    public function find(int $id): ?Wallet
    {
        return $this->model->find($id);
    }
}
