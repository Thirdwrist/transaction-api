<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferTable extends Migration
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
            $table->unsignedBigInteger('user_id');
            $table->char('status');
            $table->bigInteger('amount');
            $table->unsignedBigInteger('from_account');
            $table->unsignedBigInteger('to_account');
            $table->char('reference');
            $table->char('currency')->default(config('data.currency_default'));
            $table->foreign('from_account')
                ->references('id')
                ->on('accounts');
            $table->foreign('to_account')
                ->references('id')
                ->on('accounts');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
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
        Schema::dropIfExists('transfer_');
    }
}
