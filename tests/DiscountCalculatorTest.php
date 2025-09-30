<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

use Freyr\TDD\DiscountCalculator;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DiscountCalculatorTest extends TestCase
{
    #[Test]
    #[DataProvider('data')]
    public function discount_calculation(
        float $subtotal,
        string $customerType,
        string $date,
        ?string $coupon,
        bool $firstOrder,
        float $expectedPrice,
    ): void
    {
        $c = new DiscountCalculator();
        $finalPrice = $c->finalPrice(
            $subtotal,
            $customerType,
            $date,
            $coupon,
            $firstOrder,
        );

        self::assertEquals($expectedPrice, $finalPrice);;
    }

    public static function data(): Generator
    {
        yield 'regular customer with no price change' => [
            'subtotal' => 1000.0,
            'customerType' => 'REG', //VIP
            'date' => '2025-09-01',
            'coupon' => null,
            'firstOrder' => false,
            'expectedPrice' => 1000.0,
        ];
    }
}
