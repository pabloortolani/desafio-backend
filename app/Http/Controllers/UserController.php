<?php

namespace App\Http\Controllers;

use App\Helpers\StatusReturn;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Exception;

class UserController extends Controller
{
    public function __construct(private UserService $service) {}

    public function store(UserRequest $request)
    {
        try {
            return response($this->service->createUserAndWallet($request->all()), StatusReturn::CREATED);
        } catch (Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }

}
