<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingService
{
    public function create(array $data): Booking
    {
        $user = Auth::user();

        $booking = $user->bookings()->create([
            ...$data,
            'status' => BookingStatus::PENDING->value,
        ]);

        // dd($booking);

        //@TODO: Send notification to user and admin
        
        return $booking;
    }
}
