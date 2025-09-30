<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

/**
 * Class StringCalculator
 *
 * @package Freyr\TDD
 */
class StringCalculator
{
    public function add(string $string): int
    {
        return array_sum(explode(',', preg_replace('/[^0-9]+/', ',', $string)));
    }
}
