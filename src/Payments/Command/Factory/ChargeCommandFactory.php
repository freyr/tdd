<?php

declare(strict_types=1);

namespace TDD\Payments\Command\Factory;

use TDD\AbstractFactory;
use TDD\Payments\Command\ChargeCommand;
use TDD\Payments\Command\CheckoutCommand;

final readonly class ChargeCommandFactory extends AbstractFactory
{
    public static function createFromCheckoutCommandAndTransactionId(
        CheckoutCommand $cmd,
        int $transactionId
    ): ChargeCommand {
        return new ChargeCommand(
            $cmd->getOrderId(),
            $cmd->getAmount(),
            $transactionId
        );
    }
}
