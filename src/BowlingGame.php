<?php

declare(strict_types=1);

namespace Freyr\TDD;

class BowlingGame
{
    private FrameCollection $frames;

    public function __construct()
    {
        $this->frames = new FrameCollection();
    }

    public function roll(int $rollScore): void
    {
        $this->frames->registerRoll($rollScore);
    }

    public function getScore(): int
    {
        return $this->frames->getScore();
    }
}
