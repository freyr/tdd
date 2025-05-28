<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests\Unit;

use Freyr\TDD\BowlingCalculator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BowlingCalculatorTest extends TestCase
{

    #[Test]
    public function shouldRegisterZeroScores(): void
    {
        $c = new BowlingCalculator();

        for ($i = 0; $i < 20; $i++) {
            $c->roll(0);
        }

        self::assertSame(0, $c->getScore());
    }


    #[Test]
    public function shouldRegisterSomePointsInFirstFrame(): void
    {
        $c = new BowlingCalculator();

        $c->roll(5);
        $c->roll(1);
        for ($i = 0; $i < 18; $i++) {
            $c->roll(0);
        }

        self::assertSame(6, $c->getScore());
    }

    #[Test]
    public function shouldRegisterSomePointsInAllFrames(): void
    {
        $c = new BowlingCalculator();

        $c->roll(1);
        $c->roll(1);
        $c->roll(1);
        $c->roll(1);
        $c->roll(1);
        $c->roll(1);
        $c->roll(1);
        $c->roll(1);
        $c->roll(1);
        $c->roll(1);

        $c->roll(1);
        $c->roll(1);
        $c->roll(1);
        $c->roll(1);
        $c->roll(1);
        $c->roll(1);
        $c->roll(1);
        $c->roll(1);
        $c->roll(1);
        $c->roll(1);

        self::assertSame(20, $c->getScore());
    }

    #[Test]
    public function shouldRegisterOneSpare(): void
    {
        $c = new BowlingCalculator();

        $c->roll(5);
        $c->roll(5);
        $c->roll(8);
        for ($i = 0; $i < 17; $i++) {
            $c->roll(0);
        }

        self::assertSame(26, $c->getScore());
    }

    #[Test]
    public function shouldRegisterOneSpareAndNextRollWithTwoHits(): void
    {
        $c = new BowlingCalculator();

        $c->roll(5);
        $c->roll(5);
        $c->roll(8);
        $c->roll(1);
        for ($i = 0; $i < 16; $i++) {
            $c->roll(0);
        }

        self::assertSame(27, $c->getScore());
    }


    #[Test]
    public function shouldRegisterOneStrikeWithTwoNExtRollsNotZeros(): void
    {
        $c = new BowlingCalculator();

        $c->roll(10);

        $c->roll(2);
        $c->roll(2);
        for ($i = 0; $i < 16; $i++) {
            $c->roll(0);
        }

        self::assertSame(18, $c->getScore());
    }


}