<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\{WalletDepositRequest, WalletTransferRequest};
use App\Services\WalletService;

class WalletController extends Controller
{
    private WalletService $service;

    public function __construct(WalletService $service)
    {
        $this->service = $service;
    }

    public function deposit(WalletDepositRequest $request)
    {
        try {
            return $this->service->deposit($request->wallet_id, $request->value);
        } catch (Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }

    public function transfer(WalletTransferRequest $request)
    {
        try {
            return $this->service->transfer($request->toArray());
        } catch (Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }
}
