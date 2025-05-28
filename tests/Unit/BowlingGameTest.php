<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests\Unit;

use Freyr\TDD\BowlingGame;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BowlingGameTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        $this->sut = new BowlingGame();
    }

    #[Test]
    public function shouldRegisterZeroScores(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $this->sut->roll(0);
        }

        self::assertSame(0, $this->sut->getScore());
    }


    #[Test]
    public function shouldRegisterSomePointsInFirstFrame(): void
    {
        $this->sut->roll(5);
        $this->sut->roll(1);
        //        for ($i = 0; $i < 18; $i++) {
        //            $this->sut->roll(0);
        //        }

        self::assertSame(6, $this->sut->getScore());
    }

    #[Test]
    public function shouldRegisterSomePointsInAllFrames(): void
    {
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);

        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);
        $this->sut->roll(1);

        self::assertSame(20, $this->sut->getScore());
    }

    #[Test]
    public function shouldRegisterOneSpare(): void
    {
        $this->sut->roll(5);
        $this->sut->roll(5);
        $this->sut->roll(8);
        for ($i = 0; $i < 17; $i++) {
            $this->sut->roll(0);
        }

        self::assertSame(26, $this->sut->getScore());
    }

    #[Test]
    public function shouldRegisterOneSpareAndNextRollWithTwoHits(): void
    {
        $this->sut->roll(5);
        $this->sut->roll(5);
        $this->sut->roll(8);
        $this->sut->roll(1);
        for ($i = 0; $i < 16; $i++) {
            $this->sut->roll(0);
        }

        self::assertSame(27, $this->sut->getScore());
    }


    #[Test]
    public function shouldRegisterOneStrikeWithTwoNExtRollsNotZeros(): void
    {
        $this->sut->roll(10);

        $this->sut->roll(2);
        $this->sut->roll(2);
        for ($i = 0; $i < 16; $i++) {
            $this->sut->roll(0);
        }

        self::assertSame(18, $this->sut->getScore());
    }

    #[Test]
    public function shouldRegisterMultipleStrikeWithTwoNextRollsNotZeros(): void
    {
        $this->sut->roll(10);
        $this->sut->roll(10);

        $this->sut->roll(2);
        $this->sut->roll(2);
        for ($i = 0; $i < 14; $i++) {
            $this->sut->roll(0);
        }

        self::assertSame(38, $this->sut->getScore());
    }

    #[Test]
    public function shouldRegisterMultipleSparesWithTwoNextRollsNotZeros(): void
    {
        $this->sut->roll(5);
        $this->sut->roll(5);

        $this->sut->roll(8);
        $this->sut->roll(2);

        $this->sut->roll(3);
        for ($i = 0; $i < 14; $i++) {
            $this->sut->roll(0);
        }

        self::assertSame(34, $this->sut->getScore());
    }

    #[Test]
    public function shouldRegisterMultipleStrikesAndSparesWithTwoNextRollsNotZeros(): void
    {
        $this->sut->roll(10);
        $this->sut->roll(10);

        $this->sut->roll(5);
        $this->sut->roll(5);

        $this->sut->roll(8);
        $this->sut->roll(2);

        $this->sut->roll(3);
        for ($i = 0; $i < 11; $i++) {
            $this->sut->roll(0);
        }

        self::assertSame(74, $this->sut->getScore());
    }


    #[Test]
    public function shouldRegisterStrikeOnlyBonusRounds(): void
    {
        for ($i = 0; $i < 18; $i++) {
            $this->sut->roll(0);
        }

        $this->sut->roll(10);
        $this->sut->roll(10);
        $this->sut->roll(10);
        self::assertSame(30, $this->sut->getScore());
    }


    #[Test]
    public function shouldRegisterSpareOnlyBonusRounds(): void
    {
        for ($i = 0; $i < 18; $i++) {
            $this->sut->roll(0);
        }

        $this->sut->roll(5);
        $this->sut->roll(5);
        $this->sut->roll(10);
        self::assertSame(20, $this->sut->getScore());
    }
}
