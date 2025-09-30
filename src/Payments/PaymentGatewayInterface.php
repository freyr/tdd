<?php

declare(strict_types=1);

namespace TDD\Payments;

use TDD\Payments\DTO\ReceiptDTO;

interface PaymentGatewayInterface
{
    public function charge(): ReceiptDTO;

    public function preparePayment(): int;
}
