<?php

declare(strict_types=1);

namespace Freyr\TDD;

class BowlingCalculator
{

    private int $frame = 1;
    private int $roll = 1;
    private array $scores = [];

    public function roll(int $rollScore): void
    {
        $this->scores[$this->frame][$this->roll] = $rollScore;
        if ($this->isSpare($this->frame -1) && $this->roll === 1) {
            $this->scores[$this->frame - 1][3] = $rollScore;
        }
        if ($this->roll === 2)
        {
            $this->frame++;
            $this->roll = 1;
        } else {
            $this->roll++;
        }
    }

    public function getScore(): int
    {
        $sum = 0;
        foreach ($this->scores as $rolls) {
            foreach ($rolls as $roll) {
                $sum += $roll;
            }
        }

        return $sum;
    }

    private function isSpare(int $previousFrameIndex): bool
    {
        return count($this->scores[$previousFrameIndex] ?? []) === 2 &&
            array_sum($this->scores[$previousFrameIndex] ?? []) === 10;
    }

    private function isStrike(int $previousFrameIndex): bool
    {
        return count($this->scores[$previousFrameIndex] ?? []) === 1 &&
            array_sum($this->scores[$previousFrameIndex] ?? []) === 10;
    }


}