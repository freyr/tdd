<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

use DateTime;
use Freyr\TDD\Discount\Commands\FinalPriceCalculateCommand;
use Freyr\TDD\Discount\DiscountCalculator;
use Freyr\TDD\Discount\DiscountCalculatorInterface;
use Freyr\TDD\Discount\Enums\CouponEnum;
use Freyr\TDD\Discount\Enums\CustomerTypeEnum;
use Freyr\TDD\Discount\Policies\BlackFridayCouponPolicy;
use Freyr\TDD\Discount\Policies\ChristmasEvePolicy;
use Freyr\TDD\Discount\Policies\NoneCouponPolicy;
use Freyr\TDD\Discount\Policies\NonNegativeValuePolicy;
use Freyr\TDD\Discount\Policies\RegularCustomerPricingPolicy;
use Freyr\TDD\Discount\Policies\RoundPolicy;
use Freyr\TDD\Discount\Policies\VipCustomerPricingPolicy;
use Freyr\TDD\Discount\PolicyPipeline;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DiscountCalculatorTest extends TestCase
{
    private function buildClass(): DiscountCalculatorInterface
    {
        return new DiscountCalculator(
            new PolicyPipeline(
                new BlackFridayCouponPolicy(),
                new VipCustomerPricingPolicy(),
                new RegularCustomerPricingPolicy(),
                new NoneCouponPolicy(),
                new RoundPolicy(),
                new ChristmasEvePolicy(),
                new NonNegativeValuePolicy()
            )
        );
    }

    public static function discountCalculatorDataProvider(): Generator
    {
        yield 'regular customer with no price change' => [
            'subtotal' => 1000,
            'customerType' => CustomerTypeEnum::REG,
            'dateTime' => new DateTime('1996-06-06'),
            'coupon' => CouponEnum::NONE,
            'firstOrder' => false,
            'expectedPrice' => 1000,
        ];
        yield 'regular customer with no coupon and amount greater than 2000 has 10% discount' => [
            'subtotal' => 2500,
            'customerType' => CustomerTypeEnum::REG,
            'dateTime' => new DateTime('1996-06-06'),
            'coupon' => CouponEnum::NONE,
            'firstOrder' => false,
            'expectedPrice' => 2250,
        ];
        yield 'regular customer has 10 value discount on 24th of December' => [
            'subtotal' => 1000,
            'customerType' => CustomerTypeEnum::REG,
            'dateTime' => new DateTime('2025-12-24'),
            'coupon' => CouponEnum::NONE,
            'firstOrder' => false,
            'expectedPrice' => 990,
        ];
        yield 'regular customer with no coupon and amount greater than 2000 has 10% discount and 10 value on 24th of December' => [
            'subtotal' => 2500,
            'customerType' => CustomerTypeEnum::REG,
            'dateTime' => new DateTime('2025-12-24'),
            'coupon' => CouponEnum::NONE,
            'firstOrder' => false,
            'expectedPrice' => 2240,
        ];
        yield 'regular customer has 10 value discount on 24th of December // minus value' => [
            'subtotal' => 5,
            'customerType' => CustomerTypeEnum::REG,
            'dateTime' => new DateTime('2025-12-24'),
            'coupon' => CouponEnum::NONE,
            'firstOrder' => false,
            'expectedPrice' => 0,
        ];
        yield 'VIP customer with amount greater than 1000 has 15% discount' => [
            'subtotal' => 1500,
            'customerType' => CustomerTypeEnum::VIP,
            'dateTime' => new DateTime('1996-06-06'),
            'coupon' => CouponEnum::NONE,
            'firstOrder' => false,
            'expectedPrice' => 1275,
        ];
        yield 'VIP customer with amount greater than 1000 has 15% discount and 10 value on 24th of December' => [
            'subtotal' => 1500,
            'customerType' => CustomerTypeEnum::VIP,
            'dateTime' => new DateTime('2025-12-24'),
            'coupon' => CouponEnum::NONE,
            'firstOrder' => false,
            'expectedPrice' => 1265,
        ];
        yield 'VIP customer with lesser greater than 1000 has 50 value discount' => [
            'subtotal' => 900,
            'customerType' => CustomerTypeEnum::VIP,
            'dateTime' => new DateTime('1996-06-06'),
            'coupon' => CouponEnum::NONE,
            'firstOrder' => false,
            'expectedPrice' => 850,
        ];
        yield 'VIP customer with lesser greater than 1000 has 50 value discount, but not less than zero #1' => [
            'subtotal' => 50,
            'customerType' => CustomerTypeEnum::VIP,
            'dateTime' => new DateTime('1996-06-06'),
            'coupon' => CouponEnum::NONE,
            'firstOrder' => false,
            'expectedPrice' => 0,
        ];
        yield 'VIP customer with lesser greater than 1000 has 50 value discount, but not less than zero #2' => [
            'subtotal' => 25,
            'customerType' => CustomerTypeEnum::VIP,
            'dateTime' => new DateTime('1996-06-06'),
            'coupon' => CouponEnum::NONE,
            'firstOrder' => false,
            'expectedPrice' => 0,
        ];
        yield 'VIP customer with lesser greater than 1000 has 50 value discount, but not less than zero #3' => [
            'subtotal' => 1,
            'customerType' => CustomerTypeEnum::VIP,
            'dateTime' => new DateTime('1996-06-06'),
            'coupon' => CouponEnum::NONE,
            'firstOrder' => false,
            'expectedPrice' => 0,
        ];
        yield 'regular customer for first order with amount equals or greater than 100 has 5% discount #1' => [
            'subtotal' => 100,
            'customerType' => CustomerTypeEnum::REG,
            'dateTime' => new DateTime('1996-06-06'),
            'coupon' => CouponEnum::NONE,
            'firstOrder' => true,
            'expectedPrice' => 95,
        ];
        yield 'regular customer for first order with amount equals or greater than 100 has 5% discount #2' => [
            'subtotal' => 200,
            'customerType' => CustomerTypeEnum::REG,
            'dateTime' => new DateTime('1996-06-06'),
            'coupon' => CouponEnum::NONE,
            'firstOrder' => true,
            'expectedPrice' => 190,
        ];
        yield 'regular customer for first order with amount lesser than 100 has not 5% discount' => [
            'subtotal' => 50,
            'customerType' => CustomerTypeEnum::REG,
            'dateTime' => new DateTime('1996-06-06'),
            'coupon' => CouponEnum::NONE,
            'firstOrder' => true,
            'expectedPrice' => 50,
        ];
        yield 'regular customer with BLACK coupon has 50% discount on Black Friday' => [
            'subtotal' => 2000,
            'customerType' => CustomerTypeEnum::REG,
            'dateTime' => new DateTime('2025-11-28'),
            'coupon' => CouponEnum::BLACK,
            'firstOrder' => false,
            'expectedPrice' => 1000,
        ];
        yield 'regular customer with BLACK coupon has 10% discount not on Black Friday' => [
            'subtotal' => 2000,
            'customerType' => CustomerTypeEnum::REG,
            'dateTime' => new DateTime('1996-06-06'),
            'coupon' => CouponEnum::BLACK,
            'firstOrder' => false,
            'expectedPrice' => 1800,
        ];
        yield 'VIP customer with BLACK coupon has 50% discount on Black Friday and 15% for amount greater than 1000 (2000 on start)' => [
            'subtotal' => 3000,
            'customerType' => CustomerTypeEnum::VIP,
            'dateTime' => new DateTime('2025-11-28'),
            'coupon' => CouponEnum::BLACK,
            'firstOrder' => false,
            'expectedPrice' => 1275,
        ];
        yield 'VIP customer with BLACK coupon has 10% discount not on Black Friday and 50 value discount for amount lesser than 1800 (2000 on start)' => [
            'subtotal' => 2000,
            'customerType' => CustomerTypeEnum::VIP,
            'dateTime' => new DateTime('1996-06-06'),
            'coupon' => CouponEnum::BLACK,
            'firstOrder' => false,
            'expectedPrice' => 1530,
        ];
    }

    #[DataProvider('discountCalculatorDataProvider')]
    public function testDiscountCalculator(
        float $subtotal,
        CustomerTypeEnum $customerType,
        DateTime $dateTime,
        CouponEnum $coupon,
        bool $firstOrder,
        float $expectedPrice
    ): void
    {
        self::assertSame(
            $expectedPrice,
            $this->buildClass()->calculateFinalPrice(
                new FinalPriceCalculateCommand(
                    ...func_get_args()
                )
            )
        );
    }
}
