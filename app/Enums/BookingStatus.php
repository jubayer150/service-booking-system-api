<?php

declare(strict_types=1);

namespace App\Enums;

enum BookingStatus: int
{
    case PENDING = 0;
    case COMPLETED = 1; // IGNORE --- Have to complete by admin or staff

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
        };
    }
}
