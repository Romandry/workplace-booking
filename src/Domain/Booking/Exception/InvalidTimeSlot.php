<?php

declare(strict_types=1);

namespace App\Domain\Booking\Exception;

final class InvalidTimeSlot extends \DomainException
{
    public static function endMustBeAfterStart(): self
    {
        return new self('End time must be before start time');
    }
}
