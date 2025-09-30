<?php

declare(strict_types=1);

namespace TDD\IsLeapYear;

use InvalidArgumentException;

final class IsLeapYearService implements IsLeapYearInterface
{
    public function isLeap(int $year): bool
    {
        if ($year <= 0) {
            throw new InvalidArgumentException(
                "Given year '$year' is not valid (must be greater than zero)."
            );
        }

        return (
            $year % 400 === 0
        ) || (
            $year % 4 === 0
            && $year % 100 !== 0
        );
    }
}
