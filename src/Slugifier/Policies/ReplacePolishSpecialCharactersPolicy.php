<?php

declare(strict_types=1);

namespace Freyr\TDD\Slugifier\Policies;

use Freyr\TDD\Slugifier\SluggingPolicyInterface;

final class ReplacePolishSpecialCharactersPolicy implements SluggingPolicyInterface
{
    public function slug(string $text): string
    {
        return str_replace(
            ['ą','ć','ę','ł','ń','ó','ś','ż','ź'],
            ['a','c','e','l','n','o','s','z','z'],
            $text
        );
    }
}
