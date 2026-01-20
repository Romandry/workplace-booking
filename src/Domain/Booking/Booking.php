<?php

declare(strict_types=1);

namespace App\Domain\Booking;

use App\Domain\Booking\ValueObject\TimeSlot;

final class Booking
{
    public function __construct(
        private string $resourceId,
        private TimeSlot $timeSlot
    ) {
    }

    public function resourceId(): string
    {
        return $this->resourceId;
    }
    public function timeSlot(): TimeSlot
    {
        return $this->timeSlot;
    }
}
