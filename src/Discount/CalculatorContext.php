<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount;

use DateTime;
use Freyr\TDD\Discount\Enums\CouponEnum;
use Freyr\TDD\Discount\Enums\CustomerTypeEnum;

final readonly class CalculatorContext
{
    public function __construct(
        private CustomerTypeEnum $customerType,
        private DateTime $date,
        private CouponEnum $coupon,
        private bool $isFirstOrder
    ) {
    }

    public function isCustomerVip(): bool
    {
        return $this->customerType->isVip();
    }

    public function isCustomerRegular(): bool
    {
        return $this->customerType->isRegular();
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function isBlackCoupon(): bool
    {
        return $this->coupon->isBlack();
    }

    public function isNoneCoupon(): bool
    {
        return $this->coupon->isNone();
    }

    public function isFirstOrder(): bool
    {
        return $this->isFirstOrder;
    }
}
