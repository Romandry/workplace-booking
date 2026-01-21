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

    public function test_it_allows_touching_time_slots_for_same_resource(): void
    {
        $aggregate = BookingAggregate::forResource('ROOM-1');

        $booking1 = new Booking(
            'ROOM-1',
            new TimeSlot(
                new DateTimeImmutable('2026-01-21 10:00:00'),
                new DateTimeImmutable('2026-01-21 11:00:00')
            )
        );
        $booking2 = new Booking(
            'ROOM-1',
            new TimeSlot(
                new DateTimeImmutable('2026-01-21 11:00:00'),
                new DateTimeImmutable('2026-01-21 12:00:00')
            )
        );

        $aggregate->add($booking1);
        $aggregate->add($booking2);

        // $this->assertTrue(true);
        $this->expectNotToPerformAssertions();
    }

    public function test_it_rejects_when_new_slot_is_inside_existing_slot(): void
    {
        $aggregate = BookingAggregate::forResource('ROOM-1');

        $booking1 = new Booking(
            'ROOM-1',
            new TimeSlot(
                new DateTimeImmutable('2026-01-21 10:00:00'),
                new DateTimeImmutable('2026-01-21 12:00:00')
            )
        );
        $booking2 = new Booking(
            'ROOM-1',
            new TimeSlot(
                new DateTimeImmutable('2026-01-21 11:00:00'),
                new DateTimeImmutable('2026-01-21 11:30:00')
            )
        );

        $aggregate->add($booking1);
        $this->expectException(OverlappingBooking::class);
        $aggregate->add($booking2);
    }

    public function test_it_rejects_when_new_slot_covers_existing_slot(): void
    {
        $aggregate = BookingAggregate::forResource('ROOM-1');

        $booking1 = new Booking(
            'ROOM-1',
            new TimeSlot(
                new DateTimeImmutable('2026-01-21 10:00:00'),
                new DateTimeImmutable('2026-01-21 11:00:00')
            )
        );
        $booking2 = new Booking(
            'ROOM-1',
            new TimeSlot(
                new DateTimeImmutable('2026-01-21 09:00:00'),
                new DateTimeImmutable('2026-01-21 12:00:00')
            )
        );

        $aggregate->add($booking1);
        $this->expectException(OverlappingBooking::class);
        $aggregate->add($booking2);
    }

    public function test_it_allows_overlapping_time_slots_for_different_resources(): void
    {
        $room1 = BookingAggregate::forResource('ROOM-1');
        $booking1 = new Booking(
            'ROOM-1',
            new TimeSlot(
                new DateTimeImmutable('2026-01-21 10:00:00'),
                new DateTimeImmutable('2026-01-21 12:00:00')
            )
        );
        $room1->add($booking1);

        $room2 = BookingAggregate::forResource('ROOM-2');
        $booking2 = new Booking(
            'ROOM-2',
            new TimeSlot(
                new DateTimeImmutable('2026-01-21 10:30:00'),
                new DateTimeImmutable('2026-01-21 11:30:00')
            )
        );
        $room2->add($booking2);

        // $this->assertTrue(true);
        $this->expectNotToPerformAssertions();
    }
}
