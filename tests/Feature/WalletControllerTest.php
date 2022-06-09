<?php

namespace Tests\Feature;

use App\Adapters\{ServiceAuthorizingAdapter, ServiceNotificationAdapter};
use App\Models\{User, UserTypes, Wallet};
use App\Repository\{TransferRepository, WalletRepository};
use App\Services\WalletService;
use Exception;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WalletControllerTest extends TestCase
{
    use DatabaseTransactions;

    private User $userStandardA;
    private Wallet $walletUserStandardA;
    private User $userStandardB;
    private Wallet $walletUserStandardB;
    private User $userShopkeeper;
    private Wallet $walletUserShopkeeper;
    private ServiceAuthorizingAdapter $serviceAuthorizingAdapter;
    private ServiceNotificationAdapter $serviceNotificationAdapter;
    private WalletRepository $walletRepository;
    private WalletService $walletService;
    private TransferRepository $transferRepository;

    public function setUp(): void
    {
        parent::setUp();

        $userTypeComum = UserTypes::factory()->create(['name' => User::COMUM]);
        $userTypeLojista = UserTypes::factory()->create(['name' => User::LOJISTA]);

        $this->userStandardA = User::factory()->create([
            'type_id' => $userTypeComum->id
        ]);
        $this->walletUserStandardA = Wallet::factory()->create([
            'user_id' => $this->userStandardA->id,
            'balance' => 50
        ]);

        $this->userStandardB = User::factory()->create([
            'type_id' => $userTypeComum->id
        ]);
        $this->walletUserStandardB = Wallet::factory()->create([
            'user_id' => $this->userStandardB->id,
            'balance' => 50
        ]);

        $this->userShopkeeper = User::factory()->create([
            'type_id' => $userTypeLojista->id
        ]);
        $this->walletUserShopkeeper = Wallet::factory()->create([
            'user_id' => $this->userShopkeeper->id,
            'balance' => 50
        ]);

        $this->createMocks();
    }

    public function testTransfer()
    {
        $payload = [
            "wallet_payer" => $this->walletUserStandardA->id,
            "wallet_payee" => $this->walletUserStandardB->id,
            "value" => 40
        ];

        $this->walletService->transfer($payload);

        $this->walletUserStandardA->refresh();
        $this->walletUserStandardB->refresh();

        $this->assertEquals(10, $this->walletUserStandardA->balance);
        $this->assertEquals(90, $this->walletUserStandardB->balance);
    }

    public function testTransferUserShopkeeperToUserStandard()
    {
        $payload = [
            "wallet_payer" => $this->walletUserShopkeeper->id,
            "wallet_payee" => $this->walletUserStandardB->id,
            "value" => 40
        ];

        try {
            $response = $this->walletService->transfer($payload);
        } catch (Exception $e) {
            $response = $e->getMessage();
        }

        $this->assertEquals("Lojistas não podem realizar transferencias!", $response);
    }

    public function testInsufficientFundsForTransfer()
    {
        $payload = [
            "wallet_payer" => $this->walletUserStandardA->id,
            "wallet_payee" => $this->walletUserStandardB->id,
            "value" => 150
        ];

        try {
            $response = $this->walletService->transfer($payload);
        } catch (Exception $e) {
            $response = $e->getMessage();
        }

        $this->assertEquals("Saldo insuficiente para tranferência!", $response);
    }

    public function testTranferValueInvalid()
    {
        $payload = [
            "wallet_payer" => $this->walletUserStandardA->id,
            "wallet_payee" => $this->walletUserStandardB->id,
            "value" => -10
        ];

        try {
            $response = $this->walletService->transfer($payload);
        } catch (Exception $e) {
            $response = $e->getMessage();
        }

        $this->assertEquals("Valor da transferência inválido!", $response);
    }

    private function createMocks()
    {
        $this->serviceAuthorizingAdapter = $this->createMock(ServiceAuthorizingAdapter::class);
        $this->serviceAuthorizingAdapter->method('execute')->willReturn(['message' => 'Autorizado']);
        $this->serviceAuthorizingAdapter->method('success')->willReturn(true);

        $this->serviceNotificationAdapter = $this->createMock(ServiceNotificationAdapter::class);
        $this->serviceNotificationAdapter->method('execute')->willReturn(['message' => 'Success']);
        $this->serviceNotificationAdapter->method('success')->willReturn(true);

        $this->createWalletService();
    }

    private function createWalletService()
    {
        $this->walletRepository = app(WalletRepository::class);
        $this->transferRepository = app(TransferRepository::class);

        $this->walletService = new WalletService(
            $this->walletRepository,
            $this->serviceAuthorizingAdapter,
            $this->serviceNotificationAdapter,
            $this->transferRepository
        );
    }
}
