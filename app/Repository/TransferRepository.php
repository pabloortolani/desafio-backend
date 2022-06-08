<?php

namespace App\Repository;

use App\Models\Transfer;

class TransferRepository
{
    private Transfer $model;

    public function __construct(Transfer $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Transfer
    {
        return $this->model->create([
            'wallet_origin_id' => $data['wallet_payer'],
            'wallet_destiny_id' => $data['wallet_payee'],
            'value' => $data['value']
        ]);
    }
}
