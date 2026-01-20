<?php

declare(strict_types=1);

namespace App\Tests\Domain\Booking;

use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingAggregate;
use App\Domain\Booking\Exception\OverlappingBooking;
use App\Domain\Booking\ValueObject\TimeSlot;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class BookingAggregateOverlapsTest extends TestCase
{
    public function test_it_rejects_overlapping_booking_for_same_resource(): void
    {
        $aggregate = BookingAggregate::forResource('ROOM-1');

        $existing = new Booking(
            'ROOM-1',
            new TimeSlot(
                new DateTimeImmutable('2026-01-21 10:00:00'),
                new DateTimeImmutable('2026-01-21 11:00:00')
            ),
        );
        $aggregate->add($existing);

        $overlapping = new Booking(
            'ROOM-1',
            new TimeSlot(
                new DateTimeImmutable('2026-01-21 10:30:00'),
                new DateTimeImmutable('2026-01-21 11:30:00')
            )
        );

        $this->expectException(OverlappingBooking::class);
        $aggregate->add($overlapping);
    }
}
