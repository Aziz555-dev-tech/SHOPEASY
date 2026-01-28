<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayPalService
{
    public static function createOrder($amount, $internalTransactionId)
    {
        // Appel API PayPal (exemple simplifié)
        $response = Http::withBasicAuth(
            config('services.paypal.client_id'),
            config('services.paypal.secret')
        )->post(config('services.paypal.base_url') . '/v2/checkout/orders', [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => $amount
                ],
                'custom_id' => $internalTransactionId
            ]]
        ]);

        if (!$response->successful()) {
            throw new \Exception("Erreur PayPal: ".$response->body());
        }

        $order = $response->json();

        return [
            'id' => $order['id'],
            'approval_url' => collect($order['links'])
                ->firstWhere('rel', 'approve')['href']
        ];
    }
}
