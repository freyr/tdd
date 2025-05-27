<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

class Calculator
{
    public function add(float $a, float $b): float
    {
        return $a + $b;
    }

    public function subtract(float $a, float $b): float
    {
        return $a - $b;
    }

    public function multiply(float $a, float $b): float
    {
        return $a * $b;
    }

    public function divide(float $a, float $b): float
    {
        if ($b === 0.0) {
            throw new InvalidArgumentException('Cannot divide by zero');
        }
        return $a / $b;
    }
}
