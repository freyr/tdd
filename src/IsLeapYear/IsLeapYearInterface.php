<?php

declare(strict_types=1);

namespace TDD\IsLeapYear;

interface IsLeapYearInterface
{
    public function isLeap(int $year): bool;
}
