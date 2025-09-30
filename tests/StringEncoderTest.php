<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

use Freyr\TDD\StringEncoder;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class StringEncoderTest extends TestCase
{
    public static function stringEncoderDataProvider(): Generator
    {
        yield [
            'input' => 'ABC',
            'shift' => 3,
            'expected' => 'DEF',
        ];

        yield [
            'input' => 'xyz',
            'shift' => 3,
            'expected' => 'abc',
        ];

        yield [
            'input' => 'Attack at dawn!',
            'shift' => 1,
            'expected' => 'Buubdl bu ebxo!',
        ];

        yield [
            'input' => 'Hello, World!',
            'shift' => -5,
            'expected' => 'Czggj, Rjmgy!',
        ];
    }

    #[DataProvider('stringEncoderDataProvider')]
    public function testStringEncoder(string $input, int $shift, string $expected): void
    {
        $this->assertEquals($expected, StringEncoder::encode($input, $shift));
    }
}
