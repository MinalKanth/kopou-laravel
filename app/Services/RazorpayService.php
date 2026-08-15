<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Thin wrapper around Razorpay's REST API (no SDK dependency — just the
 * Orders API + HMAC signature verification, which is all checkout needs).
 * Docs: https://razorpay.com/docs/api/orders/ and
 * https://razorpay.com/docs/payments/payment-gateway/web-integration/standard/build-integration/#step-3-verify-payment-signature
 */
class RazorpayService
{
    private string $keyId;
    private string $keySecret;

    public function __construct()
    {
        $this->keyId = (string) config('services.razorpay.key');
        $this->keySecret = (string) config('services.razorpay.secret');
    }

    public function isConfigured(): bool
    {
        return $this->keyId !== '' && $this->keySecret !== '';
    }

    public function publicKey(): string
    {
        return $this->keyId;
    }

    /**
     * Create a Razorpay order. Amount is in rupees; Razorpay's API wants
     * the smallest currency unit (paise), so we multiply by 100 here —
     * nowhere else in the app should do this conversion.
     */
    public function createOrder(string $receipt, float $amountInRupees): array
    {
        if (!$this->isConfigured()) {
            throw new RuntimeException('Razorpay keys are not configured. Set RAZORPAY_KEY_ID and RAZORPAY_KEY_SECRET in .env.');
        }

        $response = Http::withBasicAuth($this->keyId, $this->keySecret)
            ->asJson()
            ->post('https://api.razorpay.com/v1/orders', [
                'amount' => (int) round($amountInRupees * 100),
                'currency' => 'INR',
                'receipt' => $receipt,
                'payment_capture' => 1,
            ]);

        if ($response->failed()) {
            Log::error('Razorpay order creation failed', ['body' => $response->body()]);
            throw new RuntimeException('Could not initiate payment. Please try again.');
        }

        return $response->json();
    }

    /**
     * Verify the signature Razorpay's checkout.js hands back after a
     * successful payment. This is the step that proves the payment
     * actually happened and wasn't spoofed by the browser.
     */
    public function verifySignature(string $razorpayOrderId, string $razorpayPaymentId, string $signature): bool
    {
        $expected = hash_hmac('sha256', $razorpayOrderId.'|'.$razorpayPaymentId, $this->keySecret);

        return hash_equals($expected, $signature);
    }
}
