<?php

use App\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
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
            $table->unsignedBigInteger('user_id');
            $table->char('reference');
            $table->bigInteger('amount'); // stored in lowest currency value
            $table->char('currency', 10)->default('NGN');
            $table->unsignedBigInteger('from_account');
            $table->unsignedBigInteger('to_account');
            $table->enum('entry_type', [Transaction::CREDIT, Transaction::DEBIT]);
            $table->char('transaction_type');
            $table->char('status');
            $table->bigInteger('initial_balance');
            $table->bigInteger('current_balance');
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
        Schema::dropIfExists('transactions');
    }
}
