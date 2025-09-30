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
    }
}
