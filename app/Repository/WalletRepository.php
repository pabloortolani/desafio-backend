<?php

namespace App\Repository;

use App\Models\Wallet;

class WalletRepository
{
    private Wallet $model;

    public function __construct(Wallet $model)
    {
        $this->model = $model;
    }

    public function create(int $userId): Wallet
    {
        return $this->model->create([
            'user_id' => $userId
        ]);
    }

    public function find(int $id):? Wallet
    {
        return $this->model->find($id);
    }
}
