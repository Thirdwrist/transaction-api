<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Account;
use App\User;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'account_number'=>$faker->bankAccountNumber,
        'account_name'=> $faker->company,
        'user_id'=> factory(User::class)->create()->id,
        'balance'=> 50000000,
        'currency'=> config('data.currency_default')
    ];
});
