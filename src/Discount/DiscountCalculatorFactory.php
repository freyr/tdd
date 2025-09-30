<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount;

class DiscountCalculatorFactory
{

    public static function create(string $type): DiscountCalculator
    {
        return match($type) {
            'legacy' => new DiscountCalculatorLegacy(),
            default => new CleanDiscountCalculator(),
        };
    }
}