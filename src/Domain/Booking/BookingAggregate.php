<?php

declare(strict_types=1);

namespace App\Domain\Booking;

use App\Domain\Booking\Exception\OverlappingBooking;
use DomainException;

final class BookingAggregate
{
    /** @var Booking[] */
    private array $bookings = [];

    private function __construct(
        private string $resourceId
    ) {
    }

    public static function forResource(string $resourceId): self
    {
        return new self($resourceId);
    }

    public function add(Booking $booking): void
    {
        if ($booking->resourceId() !== $this->resourceId) {
            throw new DomainException('Booking belongs to another resource.');
        }
        foreach ($this->bookings as $existingBooking) {
            if ($existingBooking->timeSlot()->overlaps($booking->timeSlot())) {
                throw OverlappingBooking::forResource($this->resourceId);
            }
        }

        $this->bookings[] = $booking;
    }
}
