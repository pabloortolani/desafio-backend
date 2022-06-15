<?php

namespace App\Providers;

use App\Interfaces\{TransferRepositoryInterface, UserRepositoryInterface,
    UserTypesRepositoryInterface, WalletRepositoryInterface};
use App\Repository\{TransferRepository, UserRepository, UserTypesRepository, WalletRepository};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $this->app->bind(
            UserTypesRepositoryInterface::class,
            UserTypesRepository::class
        );
        $this->app->bind(
            WalletRepositoryInterface::class,
            WalletRepository::class
        );
        $this->app->bind(
            TransferRepositoryInterface::class,
            TransferRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
