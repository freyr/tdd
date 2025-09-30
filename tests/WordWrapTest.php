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

    //Jeśli w danym zbiorze występuje spacja to tam wstaw znak nowej linii.
    //Jeśli nie ma w danym zbiorze spacji to utnij po danej liczbie znaków i wstaw znak nowej linii
    public static function wordWrapDataProvider(): Generator
    {
        yield ['word\nword', 'word word', 6];
        yield ['wordwo\nrd', 'wordword', 6];
        yield ['abcd\nefgh\nij', 'abcdefghij', 4];
    }
}
