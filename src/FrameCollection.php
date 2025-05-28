<?php

declare(strict_types=1);

namespace Freyr\TDD;

class FrameCollection
{
    private array $frames;
    private Frame $currentFrame;
    private int $currentFrameNumber = 1;
    private ?Frame $previousFrame;

    public function __construct()
    {
        $this->frames = [
            1 => new Frame(),
            2 => new Frame(),
            3 => new Frame(),
            4 => new Frame(),
            5 => new Frame(),
            6 => new Frame(),
            7 => new Frame(),
            8 => new Frame(),
            9 => new Frame(),
            10 => new Frame(),
        ];

        $this->currentFrame = $this->frames[1];
        $this->previousFrame = null;
    }

    public function registerRoll(int $rollScore): void
    {
        $this->currentFrame->registerRoll($rollScore);

        // If there is a previous frame
        if ($this->previousFrame) {
            // If the previous frame is a spare, and the current frame is the first roll
            if ($this->previousFrame->isSpare() && $this->currentFrame->onFirstRoll()) {
                $this->previousFrame->registerBonusScore($rollScore);
            }

            // If the previous frame is a strike
            if ($this->previousFrame->isStrike()) {
                $this->previousFrame->registerBonusScore($rollScore);
            }
        }

        // Progress game to the new frame if possible
        if ($this->currentFrame->isCompleted() && $this->currentFrameNumber < 10) {
            $this->previousFrame = $this->frames[$this->currentFrameNumber];
            $this->currentFrameNumber++;
            $this->currentFrame = $this->frames[$this->currentFrameNumber];
        }
    }

    public function getScore(): int
    {
        $totalScore = 0;

        foreach ($this->frames as $frame) {
            $totalScore += $frame->getTotalScore();
        }

        return $totalScore;
    }
}