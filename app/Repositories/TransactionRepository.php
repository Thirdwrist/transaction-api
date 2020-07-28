<?php
namespace App\Repositories;

use App\Account;
use App\Transaction;

class TransactionRepository{

    public static function recordDebit(
        int $amount,
        int $initialBalance,
        int $currentBalance,
        $reference,
        string $currency,
        Account $from_account,
        Account $to_account,
        string $transactionType,
        string $status

    )
    {
       return Transaction::create([
           'user_id'=> $from_account->user_id,
            'amount'=> $amount,
            'initial_balance'=> $initialBalance,
            'current_balance'=> $currentBalance,
            'entry_type'=> Transaction::DEBIT,
            'transaction_type'=> $transactionType,
            'reference'=> $reference,
            'currency'=>$currency,
            'from_account'=>$from_account->id,
            'to_account'=> $to_account->id,
            'status'=> $status

        ]);
    }

    public static function recordCredit(
        int $amount,
        int $initialBalance,
        int $currentBalance,
        $reference,
        string $currency,
        Account $from_account,
        Account $to_account,
        string $transactionType,
        string $status

    ){
       return Transaction::create([
            'user_id'=> $from_account->user_id,
            'amount'=> $amount,
            'initial_balance'=> $initialBalance,
            'current_balance'=> $currentBalance,
            'entry_type'=> Transaction::CREDIT,
            'transaction_type'=> $transactionType,
            'reference'=> $reference,
            'currency'=>$currency,
            'from_account'=>$from_account->id,
            'to_account'=> $to_account->id,
            'status'=> $status

        ]);
    }

    public static function recordTransfer(
        int $amount,
        string $entryType,
        int $initialBalance,
        int $currentBalance,
        $reference,
        string $currency,
        Account $from_account,
        Account $to_account,
        string $status
    )
    {
        $params = [
            $amount,
            $initialBalance,
            $currentBalance,
            $reference,
            $currency,
            $from_account,
            $to_account,
            Transaction::TRANSFER,
            $status
        ];
        if($entryType === Transaction::DEBIT)
        {
            return self::recordDebit(...$params);
        }
        if($entryType === Transaction::CREDIT)
        {
            return self::recordCredit(...$params);
        }

        return false;
    }

    public static function query()
    {
        return Transaction::query();
    }
}
?>