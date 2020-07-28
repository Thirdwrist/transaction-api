<?php

namespace App\Jobs;

use App\Account;
use App\Services\TransferService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 4;
    public $maxExceptions = 2;

    public $amount, $currency, $reference, $from, $to;
    public function __construct(int $amount, string $currency, $reference, Account $from, Account $to)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->reference = $reference;
        $this->from = $from;
        $this->to = $to;
    }

    public function handle(TransferService $transferService)
    {

        $transferService->transferToAccount(
            $this->amount,
            $this->currency,
            $this->reference,
            $this->from,
            $this->to
        );
    }
}
