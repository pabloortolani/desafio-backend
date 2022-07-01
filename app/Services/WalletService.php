<?php

namespace App\Services;

use App\Helpers\{StatusReturn, ValidateData};
use App\Interfaces\{ExternalServicesAdapter, TransferRepositoryInterface, WalletRepositoryInterface};
use App\Adapters\{ServiceAuthorizingAdapter, ServiceNotificationAdapter};
use App\Models\{Transfer, Wallet, User};
use Exception;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function __construct(
        private WalletRepositoryInterface $walletRepository,
        private ServiceAuthorizingAdapter $serviceAuthorizingAdapter,
        private ServiceNotificationAdapter $serviceNotificationAdapter,
        private TransferRepositoryInterface $transferRepository
    ) {}

    /**
     * @throws Exception
     */
    public function deposit(int $walletId, float $value): Wallet
    {
        if (! ValidateData::validateFloatGreaterThanZero($value)) {
            throw new Exception("Valor de depósito inválido!", StatusReturn::ERROR);
        }

        $wallet = $this->walletRepository->find($walletId);

        if (empty($wallet)) {
            throw new Exception("Carteira não encontrada!", StatusReturn::ERROR);
        }

        $wallet->balance += $value;
        $wallet->save();

        $wallet->load('user:id,name,type_id', 'user.type:id,name');
        return $wallet;
    }

    /**
     * @throws Exception
     */
    public function transfer(array $data): Transfer
    {
        $wallets = $this->transferValidations($data);
        $walletOrigin = $wallets['walletOrigin'];
        $walletDestiny = $wallets['walletDestiny'];

        try {
            DB::beginTransaction();

            $this->runExternalServices($this->serviceAuthorizingAdapter);

            $walletOrigin->balance -= $data['value'];
            $walletOrigin->save();

            $walletDestiny->balance += $data['value'];
            $walletDestiny->save();

            $transfer = $this->saveHistoryTransfer($data);

            $this->runExternalServices($this->serviceNotificationAdapter);

            DB::commit();
            return $transfer;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Erro ao realizar tranferência!", StatusReturn::ERROR);
        }
    }

    /**
     * @throws Exception
     */
    private function transferValidations(array $data): array
    {
        if (! ValidateData::validateFloatGreaterThanZero($data['value'])) {
            throw new Exception("Valor da transferência inválido!", StatusReturn::ERROR);
        }

        $walletOrigin = $this->walletRepository->find($data['wallet_payer']);
        if (! $walletOrigin) {
            throw new Exception("Carteira pagadora não encontrada!", StatusReturn::ERROR);
        }

        if (! $this->userCanTransfer($walletOrigin)) {
            throw new Exception("Lojistas não podem realizar transferencias!", StatusReturn::ERROR);
        }

        if (! $this->enoughBalanceForTransfer($walletOrigin, $data['value'])) {
            throw new Exception("Saldo insuficiente para tranferência!", StatusReturn::ERROR);
        }

        $walletDestiny = $this->walletRepository->find($data['wallet_payee']);
        if (! $walletDestiny) {
            throw new Exception("Carteira beneficiária não encontrada!", StatusReturn::ERROR);
        }

        return [
            "walletOrigin" => $walletOrigin,
            "walletDestiny" => $walletDestiny
        ];
    }

    private function userCanTransfer(Wallet $wallet): bool
    {
        return in_array($wallet->user->type->name, User::TYPES_USER_CAN_TRANSFER);
    }

    private function enoughBalanceForTransfer(Wallet $wallet, float $value): bool
    {
        return $wallet->balance >= $value;
    }

    private function saveHistoryTransfer(array $data): Transfer
    {
        return $this->transferRepository->create($data);
    }

    /**
     * @throws Exception
     */
    private function runExternalServices(ExternalServicesAdapter $service): void
    {
        $returnService = $service->execute([]);
        if (! $service->success($returnService)) {
            throw new Exception("Erro ao realizar tranferência!", StatusReturn::ERROR);
        }
    }
}
