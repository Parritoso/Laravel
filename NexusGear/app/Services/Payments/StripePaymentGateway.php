<?php

namespace App\Services\Payments;

use App\Models\Pedido;
use RuntimeException;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripePaymentGateway implements PaymentGateway
{
    public function createCheckoutSession(Pedido $pedido, int $userId, string $successUrl, string $cancelUrl): PaymentSession
    {
        $this->configureStripe();

        $subtotal = (float) $pedido->lineas->sum('subtotal');
        $totalFactura = round($subtotal + ($subtotal * 0.21), 2);
        $totalEnCentimos = (int) round($totalFactura * 100);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Pedido #'.$pedido->id.' en NexusGear',
                    ],
                    'unit_amount' => $totalEnCentimos,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'metadata' => [
                'pedido_id' => (string) $pedido->id,
                'usuario_id' => (string) $userId,
            ],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);

        return new PaymentSession(
            (string) $session->id,
            (string) $session->url,
            (string) ($session->payment_status ?? 'unpaid'),
        );
    }

    public function retrieveCheckoutSession(string $sessionId): PaymentSession
    {
        $this->configureStripe();

        $session = Session::retrieve($sessionId);

        return new PaymentSession(
            (string) $session->id,
            $session->url ? (string) $session->url : null,
            (string) ($session->payment_status ?? 'unpaid'),
        );
    }

    private function configureStripe(): void
    {
        $secret = config('services.stripe.secret');

        if (! $secret) {
            throw new RuntimeException(__('messages.payment_not_configured'));
        }

        Stripe::setApiKey($secret);
    }
}
