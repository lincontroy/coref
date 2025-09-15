<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayheroResponse;

class PayheroController extends Controller
{
    public function handleCallback(Request $request)
    {
        $payload = $request->all();

        // PayHero puts actual data inside "response"
        $response = $payload['response'] ?? [];

        PayheroResponse::create([
            'merchant_request_id'     => $response['MerchantRequestID'] ?? null,
            'checkout_request_id'     => $response['CheckoutRequestID'] ?? null,
            'result_code'             => $response['ResultCode'] ?? null,
            'amount'                  => $response['Amount'] ?? null,
            'mpesa_receipt_number'    => $response['MpesaReceiptNumber'] ?? null,
            'phone'                   => $response['Phone'] ?? null,
            'external_reference'      => $response['ExternalReference'] ?? null,
            'status'                  => $response['Status'] ?? null,
            'result_desc'             => $response['ResultDesc'] ?? null,
            'service_wallet_balance'  => $response['ServiceWalletBalance'] ?? null,
            'payment_wallet_balance'  => $response['PaymentWalletBalance'] ?? null,
            'channel_id'              => $response['ChannelID'] ?? null,
            'raw_response'            => json_encode($payload),
        ]);

        return response()->json(['success' => true]);
    }
}
