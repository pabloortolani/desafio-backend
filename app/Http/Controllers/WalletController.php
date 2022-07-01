<?php

namespace App\Http\Controllers;

use App\Helpers\StatusReturn;
use Exception;
use App\Http\Requests\{WalletDepositRequest, WalletTransferRequest};
use App\Services\WalletService;

class WalletController extends Controller
{
    public function __construct(private WalletService $service) {}

    public function deposit(WalletDepositRequest $request)
    {
        try {
            return response($this->service->deposit($request->wallet_id, $request->value), StatusReturn::SUCCESS);
        } catch (Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }

    public function transfer(WalletTransferRequest $request)
    {
        try {
            return response($this->service->transfer($request->toArray()), StatusReturn::SUCCESS);
        } catch (Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }
}
