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
        // Additional info: header line starts with // and ends with a newline (\n)
        // Header line can redefine the separator
        // This info can be shared with a driver as comment if necessary
        yield 'custom_separator_also_sums_numbers' => ['//;\n3;10', 13];
        // Additional info: custom separator can be any string, [ and ] are the boundary of the separator (they are not included into separator itself)
        yield 'custom_separator_longer_than_one_char' => ['//[***]\n4***10', 14];
        // Additional info: custom separator can be any string
        // \n is another default separator
        yield 'custom_separator_longer_than_one_char_and_multiple_lines' => ['//[***]\n3***10\n10***3', 26];
    }
}