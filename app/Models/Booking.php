<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'booking_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'status' => BookingStatus::class,
        ];
    }

    public function scopeForCustomer(Builder $query)
    {
        $query->where('user_id', Auth::id());
    }

    public function scopeSearch(Builder $query, string $keyword)
    {
        return $query->where(function (Builder $query) use ($keyword) {
            $query->whereHas('service', fn(Builder $serviceQuery) => 
                $serviceQuery->where('name', 'like', "%{$keyword}%")
            );

            if (array_key_exists('user', $query->getEagerLoads())) {
                $query->orWhereHas('user', fn($userQuery) => $userQuery->where('name', 'like', "%{$keyword}%"));
            }
        });
    }

    public function scopeSortBy(Builder $query, ?string $column = null, string $direction = 'asc'): Builder
    {
        $allowed = ['id', 'status', 'booking_date', 'created_at'];

        if ($column && in_array($column, $allowed, true)) {
            return $query->orderBy($column, strtolower($direction) === 'desc' ? 'desc' : 'asc');
        }

        return $query;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
