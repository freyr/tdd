<?php

declare(strict_types=1);

namespace Freyr\TDD\Slugifier;

interface SlugifierInterface
{
    public function slugify(string $text): string;
}
