<?php

namespace App\Services;

use App\Interfaces\ExternalServicesAdapter;
use App\Adapters\{ServiceAuthorizingAdapter, ServiceNotificationAdapter};
use App\Helpers\ValidateData;
use App\Models\{Transfer, Wallet, User};
use App\Repository\{TransferRepository, WalletRepository};
use Exception;
use Illuminate\Support\Facades\DB;

class WalletService
{
    private WalletRepository $walletRepository;
    private ServiceAuthorizingAdapter $serviceAuthorizingAdapter;
    private ServiceNotificationAdapter $serviceNotificationAdapter;
    private TransferRepository $transferRepository;

    public function __construct(
        WalletRepository $walletRepository,
        ServiceAuthorizingAdapter $serviceAuthorizingAdapter,
        ServiceNotificationAdapter $serviceNotificationAdapter,
        TransferRepository $transferRepository
    )
    {
        $this->walletRepository = $walletRepository;
        $this->serviceAuthorizingAdapter = $serviceAuthorizingAdapter;
        $this->serviceNotificationAdapter = $serviceNotificationAdapter;
        $this->transferRepository = $transferRepository;
    }

    /**
     * @throws Exception
     */
    public function deposit(int $walletId, float $value): Wallet
    {
        if (! ValidateData::validateFloatGreaterThanZero($value)) {
            throw new Exception("Valor de depósito inválido!", 400);
        }

        $wallet = $this->walletRepository->find($walletId);

        if (empty($wallet)) {
            throw new Exception("Carteira não encontrada!", 400);
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

            $walletOrigin->balance -= $data['value'];
            $walletOrigin->save();

            $walletDestiny->balance += $data['value'];
            $walletDestiny->save();

            $transfer = $this->saveHistoryTransfer($data);

            $this->runExternalServices($this->serviceAuthorizingAdapter);
            $this->runExternalServices($this->serviceNotificationAdapter);

            DB::commit();
            return $transfer;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Erro ao realizar tranferência!", 404);
        }
    }

    /**
     * @throws Exception
     */
    private function transferValidations(array $data): array
    {
        if (! ValidateData::validateFloatGreaterThanZero($data['value'])) {
            throw new Exception("Valor da transferência inválido!", 400);
        }

        $walletOrigin = $this->walletRepository->find($data['wallet_payer']);
        if (! $walletOrigin) {
            throw new Exception("Carteira pagadora não encontrada!", 400);
        }

        if (! $this->userCanTransfer($walletOrigin)) {
            throw new Exception("Lojistas não podem realizar transferencias!", 400);
        }

        if (! $this->enoughBalanceForTransfer($walletOrigin, $data['value'])) {
            throw new Exception("Saldo insuficiente para tranferência!", 400);
        }

        $walletDestiny = $this->walletRepository->find($data['wallet_payee']);
        if (! $walletDestiny) {
            throw new Exception("Carteira beneficiária não encontrada!", 400);
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
            throw new Exception("Erro ao realizar tranferência!", 400);
        }
    }
}
