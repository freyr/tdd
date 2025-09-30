<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class WordWrapTest extends TestCase
{
    public static function wordWrapDataProvider(): Generator
    {
        yield ['abcabc', 3, 'abc\nabc'];
        yield ['asddasddasdd', 4, 'asdd\nasdd\nasdd'];
        yield ['asd', 3, 'asd'];
        yield ['', 1, ''];
        yield ['word word', 6, 'word\nword'];
        yield ['foo\nbar baz', 5, 'foo\nbar\nbaz'];
    }

    #[DataProvider('wordWrapDataProvider')]
    public function testWordWrap(string $text, int $width, string $result): void
    {
        self::assertEquals(
            $result,
            WordWrap::wrap($text, $width)
        );
    }

    public static function wordWrapThrowsExceptionOnInvalidWidthDataProvider(): Generator
    {
        yield [-1];
        yield [0];
    }


    #[DataProvider('wordWrapThrowsExceptionOnInvalidWidthDataProvider')]
    public function testWordWrapThrowsExceptionOnInvalidWidth(int $width): void
    {
        self::expectException(InvalidArgumentException::class);

        WordWrap::wrap('', $width);
    }
}
