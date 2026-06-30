<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    private string $clientKey;

    public function __construct()
    {
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');

        if (empty($serverKey) || empty($clientKey)) {
            throw new \RuntimeException(
                'Midtrans key belum dikonfigurasi. Pastikan SECRET dan ' .
                'MIDTRANS_CLIENT_KEY sudah diisi di file .env, lalu jalankan: ' .
                'php artisan config:clear'
            );
        }

        $this->clientKey = $clientKey;

        Config::$serverKey    = $serverKey;
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized  = config('midtrans.is_sanitized', true);
        Config::$is3ds        = config('midtrans.is_3ds', true);
    }

    public function createSnapToken(array $params): array
    {
        $orderId     = $params['order_id'];
        $grossAmount = (int) $params['gross_amount'];

        $nameParts = explode(' ', $params['customer_name'] ?? 'Customer', 2);
        $firstName = $nameParts[0];
        $lastName  = $nameParts[1] ?? '';

        $snapParams = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'email'      => $params['customer_email'] ?? '',
                'phone'      => $params['customer_phone'] ?? '',
            ],
            'item_details' => $params['items'] ?? [
                [
                    'id'       => $params['event_id'] ?? '1',
                    'price'    => $grossAmount,
                    'quantity' => 1,
                    'name'     => $params['event_name'] ?? 'Event Ticket',
                ],
            ],
            'enabled_payments' => [
                'credit_card',
                'bca_va', 'bni_va', 'bri_va', 'mandiri_bill', 'permata_va',
                'gopay', 'shopeepay', 'dana',
                'qris',
                'indomaret', 'alfamart',
            ],
            'expiry' => [
                'unit'     => 'hour',
                'duration' => 24,
            ],
        ];

        $snapToken = Snap::getSnapToken($snapParams);

        $baseSnapUrl = config('midtrans.is_production', false)
            ? 'https://app.midtrans.com/snap/v2/vtweb'
            : 'https://app.sandbox.midtrans.com/snap/v2/vtweb';

        return [
            'token'        => $snapToken,
            'redirect_url' => "{$baseSnapUrl}/{$snapToken}",
        ];
    }

    public function checkStatus(string $orderId): array
    {
        $status = Transaction::status($orderId);
        return json_decode(json_encode($status), true);
    }

    public function verifySignature(
        string $orderId,
        string $statusCode,
        string $grossAmount,
        string $signatureKey
    ): bool {
        $serverKey = config('midtrans.server_key');
        $hash = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        return hash_equals($hash, $signatureKey);
    }

    public function mapStatus(string $transactionStatus, string $fraudStatus = ''): string
    {
        return match($transactionStatus) {
            'capture'    => ($fraudStatus === 'accept' || $fraudStatus === '') ? 'paid' : 'cancelled',
            'settlement' => 'paid',
            'pending'    => 'pending',
            'deny', 'cancel', 'expire' => 'cancelled',
            default      => 'pending',
        };
    }

    public function getClientKey(): string
    {
        return $this->clientKey;
    }
}
