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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_wallet_id');
            $table->unsignedBigInteger('recipient_wallet_id');
            $table->foreign('sender_wallet_id')->references('id')->on('wallets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('recipient_wallet_id')->references('id')->on('wallets')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['stoker', 'retrait', 'envoyer']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
