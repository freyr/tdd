<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class StringCalculatorTest extends TestCase
{
    #[Test]
    #[DataProvider('data')]
    public function string_add(string $input, int $result): void
    {
        $c = new StringCalculator();
        self::assertEquals($result, $c->add($input));
    }

    public static function data(): Generator
    {
        yield 'empty_input_returns_zero' => ['', 0];
        yield 'single_digit_returns_itself' => ['3', 3];
        yield 'default_separator_sums_numbers' => ['3,10', 13];
    }
}