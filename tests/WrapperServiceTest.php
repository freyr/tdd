<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

use Freyr\TDD\WrapperService;
use Freyr\TDD\WrapperServiceInterface;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WrapperServiceTest extends TestCase
{
    private function buildClass(): WrapperServiceInterface
    {
        return new WrapperService();
    }

    public static function wrapDataProvider(): Generator
    {
        yield 'wrap between words with higher limit than first space #1' => ['word word', 6, "word\nword"];
        yield 'wrap between words with higher limit than first space #2' => ['adrian wojownik', 8, "adrian\nwojownik"];
        yield 'wrap between words with higher limit than first space #3' => ['katarzyna czubakiewicz', 12, "katarzyna\nczubakiewicz"];

        yield 'wrap between words after second space, because its closer to limit' => ['piłka nożna jest super', 13, "piłka nożna\njest super"];
        yield 'both sides are less then 13 characters, so only one new line' => ['pushuję kod na proda', 13, "pushuję kod\nna proda"];
    }

    #[Test]
    #[DataProvider('wrapDataProvider')]
    public function testWrap(string $input, int $length, string $expectedResult): void
    {
        self::assertEquals(
            $expectedResult,
            $this->buildClass()->wrap($input, $length)
        );
    }
}
