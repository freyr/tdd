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
    public function string_add(string $text, int $width, string $result): void
    {
        $wordWrap = new WordWrap();
        self::assertEquals($result, $wordWrap->wrap($text, $width));
    }

    public static function data(): Generator
    {
        yield 'abc_shift' => ['abcdefg', 4, 'abcd\nefg'];
    }
}
