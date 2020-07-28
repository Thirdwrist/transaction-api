<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessTransfer;
use App\Repositories\AccountRepository;
use App\Repositories\TransferRepository;
use App\Rules\AccountBelongsToUser;
use App\Rules\CanAffordTransaction;
use App\Services\TransferService;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class TransferController extends Controller
{
    protected $transferService;
    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    public function store(Request $req)
    {
        Auth::loginUsingId(1);
        $account = $req->get('from_account_number');
        $validate = Validator::make($req->all(), [
            'from_account_number'=> ['required', new AccountBelongsToUser($req->user())],
            'amount'=> ['required', 'integer', 'min:1000'],
            'to_account_number'=> ['required', 'numeric', 'exists:accounts,account_number'],
            'reference'=> ['required'],
            'currency'=> ['required', Rule::in(config('data.currencies'))],
        ])->sometimes('amount',[new CanAffordTransaction($account)], function($input){
            return $input->from_account_number !== null;
        });

        if($validate->fails())
        {
            throw new ValidationException($validate);
        }


        /*
         * Check if in queue
         * If in queue, then return response saying still in queue
         * if transaction fails, return correct response
         * If transaction passes, return correct response no matter how many requests made
         * */

        $this->transferService->processTransfer(
            $req->user(),
            $req->get('amount'),
            $req->get('currency'),
            $req->get('reference'),
            $req->get('from_account_number'),
            $req->get('to_account_number')
        );
        $transRes = $this->transferStatus($req->get('reference'));
        return response()->json([
            'status'=> Response::$statusTexts[$transRes['code']],
            'message'=> $transRes['message'],
            'data'=>[
                'transaction_type'=>'transfer',
                'from_account_number'=> $transRes['transfer']->fromAccount->account_number,
                'to_account_number'=> $transRes['transfer']->toAccount->account_number,
                'reference'=> $transRes['transfer']->reference,
                'amount'=> $transRes['transfer']->amount,
                'currency'=> $transRes['transfer']->currency
            ]
        ], 200);
    }

    protected function transferStatus($ref)
    {
        $transfer = TransferRepository::getTransferByRef($ref);
        if($transfer === null || $transfer->status === Transaction::FAILED)
        {
            return [
                'code'=> Response::HTTP_SERVICE_UNAVAILABLE,
                'message'=> 'Service not available at the moment',
                'transfer'=> $transfer,
            ];
        }
        if($transfer->status === Transaction::PENDING)
        {
            return [
                'code'=> Response::HTTP_PROCESSING,
                'message'=> 'Successfully queued Transfer',
                'transfer'=> $transfer,
            ];
        }
        if($transfer->status === Transaction::SUCCESS)
        {
            return [
                'code'=> Response::HTTP_OK,
                'message'=> 'Successfully  Transferred',
                'transfer'=> $transfer,
            ];
        }
    }
}
