<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class WordWrapTest extends TestCase
{
    public static function wordWrapDataProvider(): Generator
    {
        yield ['abcabc', 3, "abc\nabc"];
        yield ['asddasddasdd', 4, "asdd\nasdd\nasdd"];
    }

    #[DataProvider('wordWrapDataProvider')]
    public function testWordWrap(string $text, int $width, string $result): void
    {
        self::assertEquals(
            $result,
            WordWrap::wrap($text, $width)
        );
    }
}
