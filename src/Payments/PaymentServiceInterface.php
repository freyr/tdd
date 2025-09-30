<?php

declare(strict_types=1);

namespace TDD\Payments;

use TDD\Payments\Command\CheckoutCommand;
use TDD\Payments\DTO\ReceiptDTO;

interface PaymentServiceInterface
{
    public function checkout(CheckoutCommand $cmd): ?ReceiptDTO;
}
