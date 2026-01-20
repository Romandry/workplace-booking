<?php

declare(strict_types=1);

namespace App\Tests\Domain\Booking;

use App\Domain\Booking\Exception\InvalidTimeSlot;
use App\Domain\Booking\ValueObject\TimeSlot;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class TimeSlotTest extends TestCase
{
    public function test_it_rejects_end_before_or_equal_start(): void
    {
        $start = self::dt('2026-01-20 10:00:00');
        $end   = self::dt('2026-01-20 10:00:00');

        $this->expectException(InvalidTimeSlot::class);
        $this->expectExceptionMessage('End time must be before start time');

        new TimeSlot($start, $end);
    }

    public function test_it_allows_valid_slot(): void
    {
        $start = self::dt('2026-01-20 10:00:00');
        $end   = self::dt('2026-01-20 10:00:00');

        $slot = new TimeSlot($start, $end);

        $this->assertSame($start, $slot->start);
        $this->assertSame($end, $slot->end);
    }

    private static function dt(string $value): DateTimeImmutable
    {
        $dt = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value);

        self::assertInstanceOf(\DateTimeImmutable::class, $dt);

        return $dt;
    }
}
