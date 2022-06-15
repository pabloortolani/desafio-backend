<?php

namespace App\Repository;

use App\Helpers\HandleData;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private User $model) {}

    public function create(array $data): User
    {
        return $this->model->create([
            'name' => $data['name'],
            'document' => HandleData::onlyNumber($data['document']),
            'type_id' => $data['type_id'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
    }

    public function findByDocument(string $document): ?User
    {
        return $this->model->where('document', HandleData::onlyNumber($document))->first();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }
}
