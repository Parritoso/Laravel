<?php

namespace Tests\Fakes;

use App\Models\Pedido;
use App\Services\Payments\PaymentGateway;
use App\Services\Payments\PaymentSession;

class PaidPaymentGateway implements PaymentGateway
{
    public const SESSION_ID = 'test_paid_session';

    public function createCheckoutSession(Pedido $pedido, int $userId, string $successUrl, string $cancelUrl): PaymentSession
    {
        return new PaymentSession(
            self::SESSION_ID,
            'https://payments.test/checkout/'.self::SESSION_ID,
            'unpaid',
        );
    }

    public function retrieveCheckoutSession(string $sessionId): PaymentSession
    {
        return new PaymentSession($sessionId, null, 'paid');
    }
}
