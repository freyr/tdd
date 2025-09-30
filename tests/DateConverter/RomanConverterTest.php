<?php

declare(strict_types=1);

namespace TDD\Tests\DateConverter;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use TDD\DateConverter\RomanConverter;
use TDD\Tests\AbstractTestCase;

final class RomanConverterTest extends AbstractTestCase
{
    public static function conversionDataProvider(): Generator
    {
        yield '1 is I' => [1, 'I'];
        yield '3 is II' => [2, 'II'];
        yield '3 is III' => [3, 'III'];
        yield '4 is IV' => [4, 'IV'];
        yield '5 is V' => [5, 'V'];
        yield '6 is VI' => [6, 'VI'];
        yield '7 is VII' => [7, 'VII'];
        yield '8 is VIII' => [8, 'VIII'];
        yield '9 is IX' => [9, 'IX'];
        yield '10 is X' => [10, 'X'];
    }

    #[DataProvider('conversionDataProvider')]
    public function testConversion(int $number, string $expectedResult): void
    {
        $this->assertSame(
            $expectedResult,
            new RomanConverter($number)->convert()
        );
    }
}
