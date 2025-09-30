<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

use Freyr\TDD\Cesar;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CesarTest extends TestCase
{
    #[DataProvider('cesarProvider')]
    public function testCesar(string $text, int $shift, string $result): void
    {
        $cesar = new Cesar();

        $this->assertEquals($result, $cesar->encode($text, $shift));
    }

    public static function cesarProvider(): Generator
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
            'text' => 'Attack at dawn!',
            'shift' => 1,
            'result' => 'Buubdl bu ebxo!',
        ];
        yield [
            'text' => 'Hello, World!',
            'shift' => -5,
            'result' => 'Czggj, Rjmgy!',
        ];
    }
}
