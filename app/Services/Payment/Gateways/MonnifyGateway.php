<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\DTOs\PaymentInitResult;
use App\Services\Payment\DTOs\PaymentVerifyResult;
use App\Services\Payment\PaymentApi\MonnifyApi;
use Illuminate\Support\Facades\Log;
use Exception;

class MonnifyGateway implements PaymentGatewayInterface
{
    public function initialize($invoice, string $email, string $phone, string $callbackUrl): PaymentInitResult
    {
        $invoiceId = $invoice->id ?? $invoice['id'];
        $amount = (float) ($invoice->payment_amount ?? $invoice['payment_amount'] ?? 0);
        $contractCode = \App\Models\Setting::where('key', 'monnify_contract_code')->value('value') ?: config('services.monnify.contract_code');
        $prefix = $invoice->prefix ?? $invoice['prefix'] ?? 'INV';
        $reference = $prefix . '-' . $invoiceId . '-' . time();

        try {
            $api = MonnifyApi::getInstance();
            $response = $api->post('api/v1/merchant/transactions/init-transaction', [
                'amount' => $amount,
                'customerName' => $invoice->author_name ?? 'Author',
                'customerEmail' => $email,
                'paymentReference' => $reference,
                'paymentDescription' => 'payment  #' . $invoiceId,
                'currencyCode' => 'NGN',
                'contractCode' => $contractCode,
                'redirectUrl' => $callbackUrl,
                'paymentMethods' => ['CARD', 'ACCOUNT_TRANSFER']
            ]);

            if (isset($response['requestSuccessful']) && $response['requestSuccessful'] === true) {
                $data = $response['responseBody'];
                return new PaymentInitResult(
                    reference: $reference,
                    redirectUrl: $data['checkoutUrl'],
                    rawResponse: json_encode($response)
                );
            }

            throw new Exception('Monnify initialization failed: ' . ($response['responseMessage'] ?? 'Unknown error'));

        } catch (\Throwable $e) {
            throw new Exception('Monnify initialization failed: ' . $e->getMessage());
        }
    }

    public function verify(string $reference): PaymentVerifyResult
    {
        if (empty($reference)) {
            throw new Exception('Transaction reference is required.');
        }

        try {
            $api = MonnifyApi::getInstance();
            $response = $api->get('api/v1/merchant/transactions/query', [
                'paymentReference' => $reference
            ]);

            if (isset($response['requestSuccessful']) && $response['requestSuccessful'] === true) {
                $data = $response['responseBody'];

                if (($data['paymentStatus'] ?? '') !== 'PAID') {
                    // Return 0 amount paid instead of throwing exception for pending payments
                    return new PaymentVerifyResult(
                        amountPaid: 0,
                        paymentDate: date('Y-m-d H:i:s'),
                        gateway: 'monnify',
                        rawResponse: json_encode($data),
                        reference: $reference
                    );
                }

                return new PaymentVerifyResult(
                    amountPaid: (float) ($data['amountPaid'] ?? 0),
                    paymentDate: $data['completedOn'] ?? date('Y-m-d H:i:s'),
                    gateway: 'monnify',
                    rawResponse: json_encode($data),
                    reference: $reference
                );
            }

            throw new Exception($response['responseMessage'] ?? 'Transaction not found');

        } catch (\Throwable $e) {
            Log::error('Monnify verification error: ' . $e->getMessage());
            throw new Exception('Unable to verify Monnify transaction: ' . $e->getMessage());
        }
    }

    public function webhook(array $payload, array $headers, string $rawBody): ?PaymentVerifyResult
    {
        $signature = $headers['monnify-signature'][0] ?? $headers['Monnify-Signature'][0] ?? null;
        $secretKey = \App\Models\Setting::where('key', 'monnify_secret_key')->value('value') ?: config('services.monnify.secret_key');

        if (!$signature || !$secretKey) {
            return null;
        }

        $computedSignature = hash_hmac('sha512', $rawBody, $secretKey);

        if (!hash_equals($computedSignature, $signature)) {
            return null;
        }

        $reference = $payload['paymentReference'] ?? null;

        if (!$reference) {
            return null;
        }

        try {
            return $this->verify($reference);
        } catch (\Exception $e) {
            Log::error('Monnify Webhook verification failed for ' . $reference . ': ' . $e->getMessage());
            return null;
        }
    }
}
