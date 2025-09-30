<?php

declare(strict_types=1);

namespace Freyr\TDD\Slugifier;

final readonly class Slugifier implements SlugifierInterface
{
    public function __construct(
        private PolicyPipeline $pipeline
    ) {
    }

    public function slugify(string $text): string
    {
        return $this->pipeline->run($text);
    }
}
