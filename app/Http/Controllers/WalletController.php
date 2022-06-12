<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\{WalletDepositRequest, WalletTransferRequest};
use App\Services\WalletService;

class WalletController extends Controller
{
    public function __construct(private WalletService $service) {}

    public function deposit(WalletDepositRequest $request)
    {
        try {
            return response($this->service->deposit($request->wallet_id, $request->value), 200);
        } catch (Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }

    public function transfer(WalletTransferRequest $request)
    {
        try {
            return response($this->service->transfer($request->toArray()), 200);
        } catch (Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }
}
