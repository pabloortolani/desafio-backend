<?php

namespace App\Service;

use App\Helpers\ValidateData;
use App\Models\User;
use App\Repository\{UserRepository, UserTypesRepository, WalletRepository};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserService
{
    private UserRepository $userRepository;
    private UserTypesRepository $userTypesRepository;
    private WalletRepository $walletRepository;

    public function __construct(
        UserRepository $userRepository,
        UserTypesRepository $userTypesRepository,
        WalletRepository $walletRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->userTypesRepository = $userTypesRepository;
        $this->walletRepository = $walletRepository;
    }

    /**
     * @throws Exception
     */
    public function createUserAndWallet(Request $request): User
    {
        $this->canCreateUser($request->toArray());

        try {
            DB::beginTransaction();

            $userType = $this->userTypesRepository->findByName($request->type);
            if (empty($userType)) {
                throw new Exception("Tipo de usuário inválido!", 400);
            }

            $user = $this->userRepository->create(array_merge($request->toArray(), ["type_id" => $userType->id]));
            $this->walletRepository->create($user->id);

            $user->load('wallet');

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Erro ao criar usuário!", 404);
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
            throw new Exception("Documento inválido!", 400);
        }
    }

    /**
     * @throws Exception
     */
    private function validateDocumentIsNotDuplicated(string $document): void
    {
        if (! empty($this->userRepository->findByDocument($document))) {
            throw new Exception("Documento duplicado!", 400);
        }
    }

    /**
     * @throws Exception
     */
    private function validateEmailIsNotDuplicated(string $email): void
    {
        if (! empty($this->userRepository->findByEmail($email))) {
            throw new Exception("E-mail duplicado!", 400);
        }
    }
}
