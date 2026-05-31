<?php

namespace App\Services\Payments;

class PaymentSession
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $url,
        public readonly string $payment_status,
    ) {
    }
}
