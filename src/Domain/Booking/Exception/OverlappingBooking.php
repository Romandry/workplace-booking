<?php

declare(strict_types=1);

namespace App\Domain\Booking\Exception;

use DomainException;

final class OverlappingBooking extends DomainException
{
    public static function forResource(string $resourceId): self
    {
        return new self(sprintf('Booking overlaps for resource "%s".', $resourceId));
    }
}
