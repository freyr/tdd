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
    public function string_add(string $text, int $shift, string $result): void
    {
        $c = new CesarCipher();
        self::assertEquals($result, $c->encode($text, $shift));
    }

    public static function data(): Generator
    {
        yield 'abc_shift' => ['abc', 3, 'def'];
    }
}
