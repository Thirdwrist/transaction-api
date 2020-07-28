<?php

use App\Account;
use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       factory(User::class, 10)->create()->each(function(User $user){
            $user->accounts()->create(factory(Account::class)->raw());
       });
    }
}
