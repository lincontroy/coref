<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayheroResponse;

class PayheroController extends Controller
{
    public function handleCallback(Request $request)
    {
        // Capture full payload
        $payload = $request->all();

        PayheroResponse::create([
            'transaction_id' => $payload['transaction_id'] ?? null,
            'status'         => $payload['status'] ?? null,
            'phone_number'   => $payload['phone_number'] ?? null,
            'amount'         => $payload['amount'] ?? null,
            'reference'      => $payload['reference'] ?? null,
            'raw_response'   => json_encode($payload),
        ]);

        return response()->json(['success' => true]);
    }
}
