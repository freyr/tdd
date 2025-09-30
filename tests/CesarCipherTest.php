<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

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

        $output = CesarCipher::moveCaseSensitiveCharacters($inputText, $offset);

        $this->assertEquals($result, $output);
    }

    public function dataProvider(): \Generator
    {
        yield ['ABC', '3', 'DEF'];
        yield ['xyz', '3', 'abc'];
    }
}
