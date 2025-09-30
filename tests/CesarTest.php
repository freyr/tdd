<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CesarTest extends TestCase
{
    #[DataProvider('cesarProvider')]
    public function testCesar(string $text, int $shift, string $result): void
    {
        $cesar = new Cesar();
        $cesar->encode();

        $this->assertEquals($result, $cesar->text($text, $shift));
    }

    public static function cesarProvider(): Generator
    {
        yield [
            'text' => 'ABC',
            'shift' => 3,
            'result' => 'DEF',
        ];
    }
}
