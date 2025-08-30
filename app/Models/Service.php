<?php

namespace App\Models;

use App\Enums\ServiceStatus;
use Illuminate\Database\Eloquent\Builder;
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

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function (Builder $query) use ($keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', ServiceStatus::ACTIVE);
    }

    public function scopeSortBy(Builder $query, ?string $column, string $direction = 'asc'): Builder
    {
        $allowed = ['id', 'status', 'price', 'created_at'];

        if ($column && in_array($column, $allowed, true)) {
            return $query->orderBy($column, strtolower($direction) === 'desc' ? 'desc' : 'asc');
        }

        return $query;
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
