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
            'customerType' => 'REG',
            'date' => '2025-09-01',
            'coupon' => null,
            'firstOrder' => false,
            'expectedPrice' => 1000.0,
        ];

        yield 'Black Friday with BLACK coupon halves the price' => [
            'subtotal' => 1000.0,
            'customerType' => 'REG',
            'date' => '2025-11-29',
            'coupon' => 'BLACK',
            'firstOrder' => false,
            'expectedPrice' => 500.0,
        ];

        yield 'BLACK coupon on normal day gives 10 percent off' => [
            'subtotal' => 200.0,
            'customerType' => 'REG',
            'date' => '2025-11-28',
            'coupon' => 'BLACK',
            'firstOrder' => false,
            'expectedPrice' => 180.0,
        ];

        yield 'VIP above 1000 gets fifteen percent off' => [
            'subtotal' => 1200.0,
            'customerType' => 'VIP',
            'date' => '2025-10-01',
            'coupon' => null,
            'firstOrder' => false,
            'expectedPrice' => 1020.0,
        ];

        yield 'VIP with at most 1000 subtracts 50' => [
            'subtotal' => 1000.0,
            'customerType' => 'VIP',
            'date' => '2025-10-01',
            'coupon' => null,
            'firstOrder' => false,
            'expectedPrice' => 950.0,
        ];

        yield 'VIP small order floors at zero' => [
            'subtotal' => 30.0,
            'customerType' => 'VIP',
            'date' => '2025-10-01',
            'coupon' => null,
            'firstOrder' => false,
            'expectedPrice' => 0.0,
        ];

        yield 'First order non-VIP gets five percent when at least 100' => [
            'subtotal' => 150.0,
            'customerType' => 'REG',
            'date' => '2025-10-01',
            'coupon' => null,
            'firstOrder' => true,
            'expectedPrice' => 142.5,
        ];

        yield 'High subtotal no coupon gets extra ten percent' => [
            'subtotal' => 2500.0,
            'customerType' => 'REG',
            'date' => '2025-10-01',
            'coupon' => null,
            'firstOrder' => false,
            'expectedPrice' => 2250.0,
        ];

        yield 'Rounding half up to two decimals' => [
            // No discounts, only rounding applied
            'subtotal' => 333.335,
            'customerType' => 'REG',
            'date' => '2025-10-01',
            'coupon' => null,
            'firstOrder' => false,
            'expectedPrice' => 333.34,
        ];

        yield 'Christmas Eve subtracts 10 at end' => [
            'subtotal' => 50.0,
            'customerType' => 'REG',
            'date' => '2025-12-24',
            'coupon' => null,
            'firstOrder' => false,
            'expectedPrice' => 40.0,
        ];

        yield 'Christmas Eve after VIP floor can go negative' => [
            'subtotal' => 30.0,
            'customerType' => 'VIP',
            'date' => '2025-12-24',
            'coupon' => null,
            'firstOrder' => false,
            'expectedPrice' => -10.0,
        ];

        yield 'Order coupon then VIP threshold' => [
            // 1100 with BLACK (non-BF): 1100*0.9=990, VIP then subtracts 50 => 940
            'subtotal' => 1100.0,
            'customerType' => 'VIP',
            'date' => '2025-11-28',
            'coupon' => 'BLACK',
            'firstOrder' => false,
            'expectedPrice' => 940.0,
        ];

        yield 'High subtotal discount applies after VIP when no coupon' => [
            // VIP first (2200 * 0.85 = 1870), then extra 10% for subtotal>=2000 and no coupon => 1683
            'subtotal' => 2200.0,
            'customerType' => 'VIP',
            'date' => '2025-10-01',
            'coupon' => null,
            'firstOrder' => false,
            'expectedPrice' => 1683.0,
        ];

        yield 'Black Friday VIP no extra high subtotal because coupon present' => [
            // 3000 with BLACK on BF => 1500, VIP (>1000) => 1275, no extra 10% because coupon is present
            'subtotal' => 3000.0,
            'customerType' => 'VIP',
            'date' => '2025-11-29',
            'coupon' => 'BLACK',
            'firstOrder' => false,
            'expectedPrice' => 1275.0,
        ];

        yield 'BLACK coupon on chrismas eve day gives 10 percent off and -10' => [
            'subtotal' => 200.0,
            'customerType' => 'REG',
            'date' => '2025-12-24',
            'coupon' => 'BLACK',
            'firstOrder' => false,
            'expectedPrice' => 170.0,
        ];

        yield 'VIP with BLACK coupon on chrismas eve day gives 10 percent off and -10' => [
            'subtotal' => 200.0,
            'customerType' => 'VIP',
            'date' => '2025-12-24',
            'coupon' => 'BLACK',
            'firstOrder' => false,
            'expectedPrice' => 120.0,
        ];

        yield 'VIP with BLACK coupon on chrismas eve, big value, day gives 10 percent off and -10' => [
            'subtotal' => 2000.0,
            'customerType' => 'VIP',
            'date' => '2025-12-24',
            'coupon' => 'BLACK',
            'firstOrder' => false,
            'expectedPrice' => 1520.0,
        ];
    }
}
