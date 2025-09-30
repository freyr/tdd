<?php

namespace Freyr\TDD\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Freyr\TDD\WordWrap;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class WordWrapTest extends TestCase
{
    #[Test]
    #[DataProvider('data')]
    public function testWordWrap(string $text, int $width): void
    {
        $w = new WordWrap();
        $this->assertEquals(strlen($w->wrap($text, $width)), $width);
        $this->assertEquals($w->wrap($text, $width), 'ABC\nD');
        $this->assertEquals($w->wrap($text, $width), 'A\n B C');
        $this->assertEquals($w->wrap($text, $width), 'AB\nCD\nEF\n');
        $this->assertEquals($w->wrap($text, $width), "foo\nbar\nbaz");
    }

    public static function data(): \Generator
    {
        return
            yield 'number_of_sign_in_line' => ['A', 3];
            yield 'add_enter_if_more_sign' => ['ABCD', 3];
            yield 'add_enter_before_space' => ['A B C', 3];
            yield 'add_enters' => ['ABCDEF', 2];
            yield 'add_enters_with_space_in_text' => ['ABCDEF', 2];
            yield 'add_enters_when_exist_space' => ["foo\nbar baz", 5];
    }
}
