<?php

use Freyr\TDD\WordWrap;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WordWrapTest extends TestCase
{
    #[DataProvider('provideWrap')]
    public function testEncode(string $text, int $width, string $expect): void
    {
        $wrap = new WordWrap();

        $this->assertEquals($expect, $wrap->wrap($text, $width));
    }

    public static function provideWrap(): Generator
    {
        yield [
            'text' => 'word word',
            'width' => 6,
            'expect' => 'word\nword',
        ];

        yield [
            'text' => 'abcdefghij',
            'width' => 4,
            'expect' => 'abcd\nefgh\nij',
        ];

        yield [
            'text' => 'a  b c',
            'width' => 3,
            'expect' => 'a\n b c',
        ];

        yield [
            'text' => 'foo\nbar baz',
            'width' => 5,
            'expect' => 'foo\nbar\nbaz',
        ];
    }
}
