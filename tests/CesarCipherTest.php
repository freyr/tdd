<?php

namespace Freyr\TDD\Tests;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CesarCipherTest extends TestCase
{
    #[Test]
    #[DataProvider('data')]
    public function encode(array $input, string $result): void
    {
        $c = new CesarCipher();
        self::assertEquals($result, $c->encode($input['text'],$input['shift']));
    }

    public static function data(): Generator
    {
        yield 'shift_leters' => [['text' => 'ABC','shift' => 3], 'DEF'];
    }
}