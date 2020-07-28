<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->char('account_name');
            $table->char('account_number'); // intentional ignoring bank names
            $table->bigInteger('balance'); //lowest currency value
            $table->char('currency', 10)->default(config('data.currency_default'));
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
        Schema::dropIfExists('accounts');
    }
}
