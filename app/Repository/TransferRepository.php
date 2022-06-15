<?php

namespace App\Repository;

use App\Interfaces\TransferRepositoryInterface;
use App\Models\Transfer;

class TransferRepository implements TransferRepositoryInterface
{
    public function __construct(private Transfer $model) {}

    public function create(array $data): Transfer
    {
        return $this->model->create([
            'wallet_origin_id' => $data['wallet_payer'],
            'wallet_destiny_id' => $data['wallet_payee'],
            'value' => $data['value']
        ]);
    }
}
