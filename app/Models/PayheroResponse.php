<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayheroResponse extends Model
{
    protected $fillable = [
        'merchant_request_id',
        'checkout_request_id',
        'result_code',
        'amount',
        'mpesa_receipt_number',
        'phone',
        'external_reference',
        'status',
        'result_desc',
        'service_wallet_balance',
        'payment_wallet_balance',
        'channel_id',
        'raw_response',
    ];
}
