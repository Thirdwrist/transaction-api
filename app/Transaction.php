<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];
    public const DEBIT = 'debit';
    public const CREDIT = 'credit';
    public const TRANSFER = 'transfer';
    public const SUCCESS = 'success';
    public const FAILED = 'failed';
    public const PENDING = 'pending';
}
