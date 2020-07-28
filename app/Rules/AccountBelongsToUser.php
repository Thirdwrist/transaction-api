<?php

namespace App\Rules;

use App\Repositories\AccountRepository;
use Illuminate\Contracts\Validation\Rule;
use App\User;
class AccountBelongsToUser implements Rule
{

    protected $user;
    public function __construct($user)
    {
        $this->user= $user;
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
        return $this->user ? AccountRepository::accountBelongsToUser($this->user, $value) : null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This account number does not belong to this user';
    }
}
