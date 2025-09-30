<?php

declare(strict_types=1);

namespace Freyr\TDD;

readonly class Context
{
    public function __construct(
       private CustomerType $customerType,
    ){}

    public function isCustomerVIP(): bool
    {
        return $this->customerType->isVip();
    }

}