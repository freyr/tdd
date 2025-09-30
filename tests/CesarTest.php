<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CesarTest extends TestCase
{
    #[DataProvider('provideCesar')]
    public function testEncode(string $text, int $shift, string $result): void
    {
        $cesar = new Cesar();

        $this->assertEquals($result, $cesar->encode($text, $shift));
    }

    public static function provideCesar(): Generator
    {
        yield [
            'text' => 'ABC',
            'shift' => 3,
            'result' => 'DEF',
        ];

        yield [
            'text' => 'xyz',
            'shift' => 3,
            'result' => 'abc',
        ];

        yield [
            'text' => 'abc',
            'shift' => 29,
            'result' => 'def',
        ];

        yield [
            'text' => 'abc',
            'shift' => -3,
            'result' => 'xyz',
        ];

        yield [
            'text' => 'Attack at dawn!',
            'shift' => 1,
            'result' => 'Buubdl bu ebxo!',
        ];
    }
}
