<?php

declare(strict_types=1);

namespace App\Domain\Booking\ValueObject;

use App\Domain\Booking\Exception\InvalidTimeSlot;
use DateTimeImmutable;

final readonly class TimeSlot
{
    public function __construct(
        public DateTimeImmutable $start,
        public DateTimeImmutable $end
    ) {
        if ($end <= $start) {
            throw InvalidTimeSlot::endMustBeAfterStart();
        }
    }

    public function startAt(): DateTimeImmutable
    {
        return $this->start;
    }
    public function endAt(): DateTimeImmutable
    {
        return $this->end;
    }
}
