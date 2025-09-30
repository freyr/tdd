<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Commands;

use DateTime;
use Freyr\TDD\Discount\Enums\CouponEnum;
use Freyr\TDD\Discount\Enums\CustomerTypeEnum;

final readonly class FinalPriceCalculateCommand
{
    public function __construct(
        private float $subtotal,
        private CustomerTypeEnum $customerType,
        private DateTime $dateTime,
        private CouponEnum $coupon,
        private bool $isFirstOrder
    ) {
    }

    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    public function getCustomerType(): CustomerTypeEnum
    {
        return $this->customerType;
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    public function getCoupon(): CouponEnum
    {
        return $this->coupon;
    }

    public function isFirstOrder(): bool
    {
        return $this->isFirstOrder;
    }
}
