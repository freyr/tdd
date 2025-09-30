<?php

declare(strict_types=1);

namespace Freyr\TDD;

final class WrapperService implements WrapperServiceInterface
{
    public function wrap(string $text, int $width): string
    {
        return wordwrap($text, $width, PHP_EOL);
    }
}
