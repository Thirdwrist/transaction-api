<?php
namespace App\Services;

use App\Account;
use App\Jobs\ProcessTransfer;
use App\Repositories\AccountRepository;
use App\Repositories\TransferRepository;
use App\User;
use function config;
use Illuminate\Support\Facades\DB;

class TransferService
{
    public function transferToAccount(int $amount, string $currency, $reference, Account $from, Account $to)
    {
        DB::transaction(function () use ($amount, $reference, $currency, $from, $to){
            AccountRepository::debitAccount($amount, $reference, $currency, $from, $to);
            AccountRepository::creditAccount($amount, $reference, $currency, $from, $to);
        }, 2);

    }

    public function processTransfer(User $user, int $amount, string $currency, $reference, $from, $to)
    {
        $params= [
            $amount,
            $currency,
            $reference,
            AccountRepository::getAcctByAcctNo($from),
            AccountRepository::getAcctByAcctNo($to)
        ];

        if(TransferRepository::getTransferByRef($reference) === null)
        {
            TransferRepository::storeTransfer($user, ...$params);
            ProcessTransfer::dispatch(...$params)->onQueue(config('data.queues.transfers'));
        }
    }
}