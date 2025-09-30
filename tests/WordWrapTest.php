<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

use Freyr\TDD\WordWrap;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class WordWrapTest extends TestCase
{
    #[Test]
    #[DataProvider('data')]
    public function wrap_test(string $text, int $width, string $result): void
    {
        $ww = new WordWrap();

        self::assertEquals($result, $ww->wrap($text, $width));
    }

    #[Test]
    public function wrap_invalid_test(): void
    {
        $ww = new WordWrap();

        self::expectException(\InvalidArgumentException::class);
        $ww->wrap("", 0);
    }

    public static function data(): Generator
    {
        yield 'wrap_1' => ["word word", 6, "word\nword"];
        yield 'wrap_2' => ["abcdefghij", 4, "abcd\nefgh\nij"];
        yield 'wrap_3' => ["", 4, ""];
        yield 'wrap_4' => ["word  word", 6, "word\nword"];
        yield 'wrap_5' => ["foo\nbar baz", 5, "foo\nbar\nbaz"];
        yield 'wrap_6' => ["a b c", 3, "a\n b c"];
    }
}