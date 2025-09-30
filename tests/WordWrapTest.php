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
    }

    public static function data(): \Generator
    {
        return
            yield 'number_of_sign_in_line' => ['A', 3];
            yield 'add_enter_if_more_sign' => ['ABCD', 3];
            yield 'add_enter_before_space' => ['A B C', 3];
    }
}
