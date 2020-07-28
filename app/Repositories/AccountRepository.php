<?php
namespace App\Repositories;

use App\Account;
use App\Transaction;
use App\User;

class AccountRepository
{
    public static function accountBelongsToUser(User $user, int $account)
    {
        return optional(self::getAccountByAccountNumber($account))->user_id === $user->id;
    }

    public static function getAccountByAccountNumber(int $account)
    {
        return Account::where('account_number', $account)
            ->first();
    }

    public static function getAcctByAcctNo(int $account)
    {
        return self::getAccountByAccountNumber($account);
    }

    private static function debit(Account $account, int $amount)
    {
        if($account->balance >= $amount)
        {
            $account->balance -= $amount;
            $account->save();
            return $account->refresh();
        }
    }

    public static function debitAccount(int $amount, $reference, String $currency, Account $from, Account $to){
        $initialBalance = $from->balance;
        $updatedAccount = self::debit($from, $amount);
        TransactionRepository::recordTransfer(
            $amount,
            Transaction::DEBIT,
            $initialBalance,
            $updatedAccount->balance,
            $reference,
            $currency,
            $from,
            $to,
            Transaction::SUCCESS
        );
    }

    private static function credit(Account $account, int $amount)
    {
        $account->balance += $amount;
        $account->save();
        return $account->refresh();
    }

    public static function creditAccount( int $amount, $reference, String $currency, Account $from, Account $to)
    {
        $initialBalance = $to->balance;
        $updatedAccount = self::credit($to, $amount);
        TransactionRepository::recordTransfer(
            $amount,
            Transaction::CREDIT,
            $initialBalance,
            $updatedAccount->balance,
            $reference,
            $currency,
            $from,
            $to,
            Transaction::SUCCESS
        );
    }
}
?>