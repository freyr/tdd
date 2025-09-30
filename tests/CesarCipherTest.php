<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

use Freyr\TDD\CesarCipher;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CesarCipherTest extends TestCase
{
    #[Test]
    #[DataProvider('dataProvider')]
    public function testZmienNazwePozdro(string $inputText, int $offset, string $result): void
    {
        /*
         * - Przesuwaj wyłącznie litery A–Z i a–z; zachowuj case. Inne znaki pozostaw bez zmian.
            - shift może być dowolną liczbą całkowitą (obsłuż modulo 26). Dopuszczalne wartości ujemne.
            - Znaki narodowe (np. ą, ę, ł) pozostaw bez zmian.
         */

        $cesarCipher = new CesarCipher();

        $this->assertEquals($result, $cesarCipher->moveCaseSensitiveCharacters($inputText, $offset));
    }

    public static function dataProvider(): Generator
    {
        yield ['ABC', 3, 'DEF'];
        yield ['xyz', 3, 'abc'];
        yield ['Attack at dawn!', 1, 'Buubdl bu ebxo!'];
    }
}
