<?php

declare(strict_types=1);

namespace Freyr\TDD;

final class WrapperService implements WrapperServiceInterface
{
    public function wrap(string $text, int $width): string
    {
        return preg_replace('/\n\s/', PHP_EOL, wordwrap($text, $width, PHP_EOL));
    }
}
