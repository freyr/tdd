<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CesarCipherTest extends TestCase
{
    #[Test]
    #[DataProvider('data')]
    public function test_encode(string $text, int $shift, string $result): void
    {
        $cesarCipher = new CesarCipher();
        self::assertEquals($result, $cesarCipher->encode($text, $shift));
    }

    public static function data(): Generator
    {
        yield ['ABC', 3, 'DEF'];
    }
}
