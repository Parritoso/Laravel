<?php

namespace App\Services\Payments;

use App\Models\Pedido;

interface PaymentGateway
{
    public function createCheckoutSession(Pedido $pedido, int $userId, string $successUrl, string $cancelUrl): PaymentSession;

    public function retrieveCheckoutSession(string $sessionId): PaymentSession;
}
