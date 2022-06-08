<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Exception;

class UserController extends Controller
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function store(UserRequest $request)
    {
        try {
            return $this->service->createUserAndWallet($request);
        } catch (Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }

}
