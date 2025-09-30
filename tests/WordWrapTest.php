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
    }

    public static function data(): \Generator
    {
        return
            yield 'number_of_sign_in_line' => ['A', 3];
    }
}
