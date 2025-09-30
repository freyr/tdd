<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class WordWrapTest extends TestCase
{
    #[Test]
    #[DataProvider('wordWrapDataProvider')]
    public function wrap(string $result, string $text, int $width): void
    {
        $wordWrapper = new WordWrapper();

        $this->assertEquals($result, $wordWrapper->wrap($text, $width));
    }

    public static function wordWrapDataProvider(): Generator
    {
        yield ['a', 'a', 1];
    }

}
