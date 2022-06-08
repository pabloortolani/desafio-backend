<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_origin_id');
            $table->unsignedBigInteger('wallet_destiny_id');
            $table->float('value');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('wallet_origin_id', 'fk_wallet_origin')
                ->references('id')->on('wallets');
            $table->foreign('wallet_destiny_id', 'fk_wallet_destiny')
                ->references('id')->on('wallets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
};
