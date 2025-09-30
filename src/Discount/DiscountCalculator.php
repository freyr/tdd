<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount;

use Freyr\TDD\Discount\Commands\FinalPriceCalculateCommand;
use Freyr\TDD\Discount\Factory\CalculatorContextFactory;

final readonly class DiscountCalculator implements DiscountCalculatorInterface
{
    public function __construct(
        private PolicyPipeline $pipeline
    ) {
    }

    public function calculateFinalPrice(FinalPriceCalculateCommand $cmd): float
    {
        return $this->pipeline->run(
            $cmd->getSubtotal(),
            CalculatorContextFactory::createFromFinalPriceCalculateCommand($cmd)
        );
    }
}
