<?php

namespace App\Rules;

use App\Account;
use App\Repositories\AccountRepository;
use Illuminate\Contracts\Validation\Rule;
use function optional;

class CanAffordTransaction implements Rule
{
    protected $account;
    public function __construct($account)
    {
        $this->account = $account ?AccountRepository::getAccountByAccountNumber($account):null;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
       return optional($this->account)->balance >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You do not have sufficient balance to perform this transaction';
    }
}
