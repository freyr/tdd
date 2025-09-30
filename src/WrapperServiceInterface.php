<?php

declare(strict_types=1);

namespace Freyr\TDD;

interface WrapperServiceInterface
{
    public function wrap(string $text, int $width): string;
}
