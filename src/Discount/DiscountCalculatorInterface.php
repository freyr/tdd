<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount;

use Freyr\TDD\Discount\Commands\FinalPriceCalculateCommand;

interface DiscountCalculatorInterface
{
    public function calculateFinalPrice(FinalPriceCalculateCommand $cmd): float;
}
