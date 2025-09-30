<?php

declare(strict_types=1);

namespace Freyr\TDD\Slugifier;

final class Slugifier
{
    public function slugify(string $text): string
    {
        $t = trim($text);
        $t = strtolower($t);
        $t = str_replace(
            ['ą','ć','ę','ł','ń','ó','ś','ż','ź'],
            ['a','c','e','l','n','o','s','z','z'],
            $t
        );
        $t = preg_replace('/&.+?;/', '', $t);
        $t = str_replace(['_', ' '], '-', $t);
        $t = preg_replace('/[^a-z0-9\-]/', '', $t);
        $t = preg_replace('/-+/', '-', $t);
        if ($t && $t[0] === '-') $t = substr($t, 1);
        if ($t && substr($t, -1) === '-') $t = substr($t, 0, -1);
        return $t ?? '';
    }
}
