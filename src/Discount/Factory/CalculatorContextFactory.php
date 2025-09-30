<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Factory;

use Freyr\TDD\Discount\CalculatorContext;
use Freyr\TDD\Discount\Commands\FinalPriceCalculateCommand;

final class CalculatorContextFactory
{
    public static function createFromFinalPriceCalculateCommand(FinalPriceCalculateCommand $cmd): CalculatorContext
    {
        return new CalculatorContext(
            $cmd->getCustomerType(),
            $cmd->getDateTime(),
            $cmd->getCoupon(),
            $cmd->isFirstOrder()
        );
    }
}
