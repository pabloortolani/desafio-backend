<?php

namespace App\Services;

use App\Helpers\{StatusReturn, ValidateData};
use App\Interfaces\{UserRepositoryInterface, UserTypesRepositoryInterface, WalletRepositoryInterface};
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserTypesRepositoryInterface $userTypesRepository,
        private WalletRepositoryInterface $walletRepository
    ) {}

    /**
     * @throws Exception
     */
    public function createUserAndWallet(array $data): User
    {
        $this->canCreateUser($data);

        try {
            DB::beginTransaction();

            $userType = $this->userTypesRepository->findByName($data['type']);
            if (empty($userType)) {
                throw new Exception("Tipo de usuário inválido!", StatusReturn::ERROR);
            }

            $user = $this->userRepository->create(array_merge($data, ["type_id" => $userType->id]));
            $this->walletRepository->create($user->id);

            $user->load('wallet');

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Erro ao criar usuário!", StatusReturn::ERROR);
        }
    }

    /**
     * @throws Exception
     */
    private function canCreateUser(array $data): void
    {
        $this->validateDocument($data['document']);
        $this->validateDocumentIsNotDuplicated($data['document']);
        $this->validateEmailIsNotDuplicated($data['email']);
    }

    /**
     * @throws Exception
     */
    private function validateDocument(string $document): void
    {
        if (! ValidateData::validateCpfOrCnpj($document)) {
            throw new Exception("Documento inválido!", StatusReturn::ERROR);
        }
    }

    /**
     * @throws Exception
     */
    private function validateDocumentIsNotDuplicated(string $document): void
    {
        if (! empty($this->userRepository->findByDocument($document))) {
            throw new Exception("Documento duplicado!", StatusReturn::ERROR);
        }
    }

    /**
     * @throws Exception
     */
    private function validateEmailIsNotDuplicated(string $email): void
    {
        if (! empty($this->userRepository->findByEmail($email))) {
            throw new Exception("E-mail duplicado!", StatusReturn::ERROR);
        }
    }
}
