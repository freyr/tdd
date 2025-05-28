<?php

declare(strict_types=1);

namespace Freyr\TDD;

class BowlingCalculator
{
    private int $frame = 1;
    private int $roll = 1;
    /** @var array<int, array<int, int>>  */
    private array $scores = [];

    public function roll(int $rollScore): void
    {
        // Special handling for the 10th frame
        if ($this->frame === 10) {
            $this->handleTenthFrameBonusRolls($rollScore);
            return;
        }

        $this->scores[$this->frame][$this->roll] = $rollScore;

        $this->handleSpareBonusScore($rollScore);
        $this->handleStrikeBonusScore($rollScore);

        // progress roll and frame indexes
        if ($this->roll === 1 && $rollScore === 10) {
            $this->frame++;
            $this->roll = 1;
            return;
        } elseif ($this->roll === 2) {
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

    private function handleSpareBonusScore(int $rollScore): void
    {
        $previousFrameIndex = $this->frame - 1;

        if (
            count($this->scores[$previousFrameIndex] ?? []) === 2
            && array_sum($this->scores[$previousFrameIndex] ?? []) === 10
            && $this->roll === 1) {
            $this->scores[$previousFrameIndex][3] = $rollScore;
        }
    }

    private function handleStrikeBonusScore(int $rollScore): void
    {
        $previousFrameIndex = $this->frame - 1;

        if (isset($this->scores[$previousFrameIndex][1])
            && $this->scores[$previousFrameIndex][1] === 10) {
            if (!isset($this->scores[$previousFrameIndex][2])) {
                $this->scores[$previousFrameIndex][2] = $rollScore;
            } elseif (!isset($this->scores[$previousFrameIndex][3])) {
                $this->scores[$previousFrameIndex][3] = $rollScore;
            }
        }
    }

    /**
     * Handle rolls and bonus logic for the 10th frame.
     */
    private function handleTenthFrameBonusRolls(int $rollScore): void
    {
        // Failsafe: do not register more than allowed rolls
        if ($this->roll > 3) {
            return;
        }

        // Only allow 3rd roll if first was strike or first two are spare
        if ($this->roll === 3) {
            $first = $this->scores[10][1] ?? 0;
            $second = $this->scores[10][2] ?? 0;
            if (!($first === 10 || ($first + $second) === 10)) {
                // Not eligible for 3rd roll
                return;
            }
        }

        if (!isset($this->scores[10])) {
            $this->scores[10] = [];
        }
        $this->scores[10][$this->roll] = $rollScore;

        $this->handleSpareBonusScore($rollScore);
        $this->handleStrikeBonusScore($rollScore);

        // If first roll is strike
        if ($this->roll === 1 && $rollScore === 10) {
            $this->roll = 2;
            return;
        }
        // If second roll is strike or spare
        if ($this->roll === 2) {
            $first = $this->scores[10][1] ?? 0;
            $second = $this->scores[10][2] ?? 0;
            if ($first === 10 || ($first + $second) === 10) {
                $this->roll = 3;
                return;
            }
            // No bonus: game ends
            return;
        }
        // If third roll, game ends
        if ($this->roll === 3) {
            return;
        }
        $this->roll++;
    }
}
