<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayheroResponse extends Model
{
    protected $fillable = [
        'transaction_id',
        'status',
        'phone_number',
        'amount',
        'reference',
        'raw_response',
    ];
}
