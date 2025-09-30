<?php

namespace Freyr\TDD\Tests;

use Freyr\TDD\CesarCipher;
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
        yield 'shift_letters' => [['text' => 'ABC','shift' => 3], 'DEF'];
        yield 'shift_letters_case_sensitive' => [['text' => 'ABc','shift' => 3], 'DEc'];
        yield 'shift_letters_special_chart_not_shift' => [['text' => 'Attack at dawn!','shift' => 1], 'Buubdl bu ebxo!'];

    }
}
