<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Integer;

class TransactionController extends Controller
{
    public function index(Integer $account, Request $request)
    {
        $request->validate([
            'amount'=> ['required', 'integer', 'min:10'],
            'currency'=> ['required', Rule::in(config('data.currencies'))],
            'entry_type'=>['required', Rule::in([Transaction::CREDIT, Transaction::DEBIT])],
        ]);
    }
}
