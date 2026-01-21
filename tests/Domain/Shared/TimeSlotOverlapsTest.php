<?php

declare(strict_types=1);

namespace App\Tests\Domain\Shared;

use App\Domain\Booking\ValueObject\TimeSlot;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class TimeSlotOverlapsTest extends TestCase
{
    public function test_it_returns_true_when_slots_overlap(): void
    {
        $interval1 = new TimeSlot(
            new DateTimeImmutable('2026-01-21 10:00:00'),
            new DateTimeImmutable('2026-01-21 11:00:00'),
        );
        $interval2 = new TimeSlot(
            new DateTimeImmutable('2026-01-21 10:30:00'),
            new DateTimeImmutable('2026-01-21 11:30:00'),
        );

        $this->assertTrue($interval1->overlaps($interval2));
    }

    public function test_it_returns_false_when_slots_only_touch(): void
    {
        $interval1 = new TimeSlot(
            new DateTimeImmutable('2026-01-21 10:00:00'),
            new DateTimeImmutable('2026-01-21 11:00:00'),
        );
        $interval2 = new TimeSlot(
            new DateTimeImmutable('2026-01-21 11:00:00'),
            new DateTimeImmutable('2026-01-21 12:00:00'),
        );

        $this->assertFalse($interval1->overlaps($interval2));
    }
}
