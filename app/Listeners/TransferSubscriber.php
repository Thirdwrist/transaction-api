<?php


namespace App\Listeners;


use App\Repositories\TransferRepository;
use App\Transaction;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use function json_decode;
use function unserialize;

class TransferSubscriber
{
    public function updateTransferOnSuccess(JobProcessed $event)
    {
        $payload = json_decode( $event->job->getRawBody() );
        $data = unserialize( $payload->data->command );
        TransferRepository::updateStatus($data->reference, Transaction::SUCCESS);
    }
    public function updateTransferOnFail(JobFailed $event)
    {
        $payload = json_decode( $event->job->getRawBody() );
        $data = unserialize( $payload->data->command);
        TransferRepository::updateStatus($data->reference, Transaction::FAILED);
    }

    public function subscribe($events)
    {
        $events->listen(
            JobProcessed::class,
            'App\Listeners\TransferSubscriber@updateTransferOnSuccess'
        );

        $events->listen(
            JobFailed::class,
            'App\Listeners\TransferSubscriber@updateTransferOnFail'
        );
    }
}