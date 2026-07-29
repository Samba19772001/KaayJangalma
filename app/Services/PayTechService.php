<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayTechService
{
    private string $apiKey;
    private string $apiSecret;
    private string $baseUrl = 'https://paytech.sn/api/payment/request-payment';

    public function __construct()
    {
        $this->apiKey    = config('services.paytech.api_key');
        $this->apiSecret = config('services.paytech.api_secret');
    }

    public function requestPayment(array $data): array
    {
        $params = [
            'item_name'    => $data['item_name'],
            'item_price'   => $data['item_price'],
            'currency'     => 'XOF',
            'ref_command'  => $data['ref_command'],
            'command_name' => $data['command_name'],
            'env'          => config('services.paytech.env', 'prod'),
            'ipn_url'      => $data['ipn_url'] ?? route('paytech.ipn'),
            'success_url'  => $data['success_url'] ?? route('paytech.success'),
            'cancel_url'   => $data['cancel_url'] ?? route('paytech.cancel'),
            'custom_field' => json_encode($data['custom_field'] ?? []),
        ];

        $response = Http::asForm()
                        ->withHeaders([
                            'API_KEY'    => $this->apiKey,
                            'API_SECRET' => $this->apiSecret,
                        ])
                        ->post($this->baseUrl, $params);

        if ($response->failed()) {
            return [
                'success' => 0,
                'message' => 'Erreur HTTP : ' . $response->status(),
            ];
        }

        return $response->json() ?? [
            'success' => 0,
            'message' => 'Réponse invalide de PayTech',
        ];
    }
}