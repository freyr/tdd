<?php

declare(strict_types=1);

namespace Freyr\TDD;

class Frame
{
    /** @var Roll[] */
    private array $rolls = [];

    /** @var Roll[] */
    private array $bonusRolls = [];


    public function registerRoll(int $currentRollScore): void
    {
        $this->rolls[] = new Roll($currentRollScore);
    }

    public function registerBonusScore(int $rollScore): void
    {
        $this->bonusRolls[] = new Roll($rollScore);
    }

    public function getScore(): int
    {
        return array_sum(array_map(fn(Roll $roll) => $roll->rollScore, $this->rolls));
    }

    public function getTotalScore(): int
    {
        return $this->getScore()
        + array_sum(array_map(fn(Roll $roll) => $roll->rollScore, $this->bonusRolls));

    }

    public function isCompleted(): bool
    {
        return $this->isStrike() || count($this->rolls) === 2;
    }

    public function onFirstRoll(): bool
    {
        return count($this->rolls) === 1;
    }

    public function isSpare(): bool
    {
        return $this->getScore() === 10 && count($this->rolls) === 2;
    }

    public function isStrike(): bool
    {
        return $this->getScore() === 10 && count($this->rolls) === 1;
    }
}