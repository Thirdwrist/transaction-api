<?php
namespace App\Repositories;


use App\Account;
use App\Transaction;
use App\Transfer;
use App\User;

class TransferRepository{
    public static function storeTransfer(User $user, int $amount, string $currency, $reference, Account $from, Account $to)
    {
        return Transfer::create([
            'user_id'=> $user->id,
            'amount'=> $amount,
            'currency'=> $currency,
            'reference'=> $reference,
            'from_account'=> $from->id,
            'to_account'=> $to->id,
            'status'=> Transaction::PENDING
        ]);
    }

    public static function getTransferByRef($ref)
    {
        return Transfer::where('reference', $ref)->first();
    }

    public static function  updateStatus($ref, $status)
    {
        $transfer = self::getTransferByRef($ref);
        $transfer->status = $status;
        $transfer->save();
    }
}