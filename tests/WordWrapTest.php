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
        yield 'wrap_word_after_width' => ['abcdefg', 4, 'abcd\nefg'];
        yield 'wrap_longer_word_after_width' => ['abcdefghij', 2, 'ab\ncd\nef\ngh\nij'];
        yield 'return_empty_when_no_word_given' => ['', 4, ''];
        yield 'return_word_when_shorter_than_width' => ['test', 5, 'test'];
        yield 'wrap_word_after_white_space' => ['word word', 6, 'word\nword'];
    }
}
