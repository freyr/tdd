<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests\Shared;

use DateTimeImmutable;
use DateTimeZone;
use Freyr\TDD\Shared\Carbon60;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class Carbon60Test extends TestCase
{
    #[Test]
    public function fromDateTime_preserves_timezone_and_microseconds(): void
    {
        $tz = new DateTimeZone('America/Los_Angeles');
        $source = new DateTimeImmutable('2024-03-10 01:59:59.123456', $tz);

        $c = Carbon60::fromDateTime($source);

        self::assertSame('America/Los_Angeles', $c->getTimezone()->getName());
        self::assertSame('2024-03-10 01:59:59.123456', $c->format('Y-m-d H:i:s.u'));
    }

    #[Test]
    #[DataProvider('christmassDayCases')]
    public function isChristmassDay_cases(string $date, bool $expected): void
    {
        $c = new Carbon60($date);
        self::assertSame($expected, $c->isChristmassDay());
    }

    public static function christmassDayCases(): Generator
    {
        yield 'isChristmassDay_true_on_dec_25' => ['2025-12-25 12:34:56', true];
        yield 'isChristmassDay_false_other_dates' => ['2025-12-24 23:59:59', false];
    }

    #[Test]
    #[DataProvider('christmasEveCases')]
    public function isChristmasEve_cases(string $date, bool $expected): void
    {
        $c = new Carbon60($date);
        self::assertSame($expected, $c->isChristmasEve());
    }

    public static function christmasEveCases(): Generator
    {
        yield 'isChristmasEve_true_on_dec_24' => ['2025-12-24 00:00:00', true];
        yield 'isChristmasEve_false_other_dates' => ['2025-12-25 00:00:00', false];
    }

    #[Test]
    #[DataProvider('blackFridayTrueCases')]
    public function isBlackFriday_true_cases(string $date, ?string $tz): void
    {
        $timezone = $tz ? new DateTimeZone($tz) : null;
        $c = new Carbon60($date, $timezone);
        self::assertTrue($c->isBlackFriday(), sprintf('Expected Black Friday for %s %s', $date, $tz ?? 'UTC default'));
    }

    public static function blackFridayTrueCases(): Generator
    {
        // Known Black Fridays (US): 2023-11-24, 2024-11-29, 2025-11-28, 2026-11-27
        yield __FUNCTION__ . '_2023_default' => ['2023-11-24 15:00:00', null]; // default timezone
        yield __FUNCTION__ . '_2024_utc' => ['2024-11-29 00:00:00', 'UTC'];
        yield __FUNCTION__ . '_2025_warsaw' => ['2025-11-28 23:59:59', 'Europe/Warsaw'];
        yield __FUNCTION__ . '_2026_ny' => ['2026-11-27 12:00:00', 'America/New_York'];

        // Timezone edge: ensure timezone is respected (same local date but different UTC)
        yield __FUNCTION__ . '_2024_kiritimati' => ['2024-11-29 00:30:00', 'Pacific/Kiritimati']; // UTC+14
        yield __FUNCTION__ . '_2024_auckland' => ['2024-11-29 00:30:00', 'Pacific/Auckland'];   // UTC+13/12
        yield __FUNCTION__ . '_2024_adak' => ['2024-11-29 00:30:00', 'America/Adak'];       // UTC-10
    }

    #[Test]
    #[DataProvider('blackFridayFalseCases')]
    public function isBlackFriday_false_cases(string $date, ?string $tz): void
    {
        $timezone = $tz ? new DateTimeZone($tz) : null;
        $c = new Carbon60($date, $timezone);
        self::assertFalse($c->isBlackFriday(), sprintf('Expected NOT Black Friday for %s %s', $date, $tz ?? 'UTC default'));
    }

    public static function blackFridayFalseCases(): Generator
    {
        // Adjacent days around Black Friday for various years
        yield __FUNCTION__ . '_2024_utc_thanksgiving' => ['2024-11-28 23:59:59', 'UTC'];     // Thanksgiving day
        yield __FUNCTION__ . '_2024_utc_saturday' => ['2024-11-30 00:00:00', 'UTC'];     // Saturday after
        yield __FUNCTION__ . '_2025_warsaw_thanksgiving' => ['2025-11-27 23:59:59', 'Europe/Warsaw']; // Thanksgiving
        yield __FUNCTION__ . '_2025_warsaw_saturday' => ['2025-11-29 00:00:00', 'Europe/Warsaw']; // Saturday
        yield __FUNCTION__ . '_2026_ny_thanksgiving' => ['2026-11-26 12:00:00', 'America/New_York']; // Thanksgiving
        yield __FUNCTION__ . '_2026_ny_saturday' => ['2026-11-28 12:00:00', 'America/New_York']; // Saturday
    }
}
