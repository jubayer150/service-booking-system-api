<?php

namespace App\Models;

use App\Enums\ServiceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'status' => ServiceStatus::class,
        ];
    }

    public function isActive(): bool
    {
        return $this->status === ServiceStatus::ACTIVE;
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
