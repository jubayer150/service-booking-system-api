<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\RoleName;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class BookingRepository
{
    public function getAll(array $filters = []): Collection|LengthAwarePaginator
    {
        $bookings = Booking::query()
            ->when(Auth::user()->hasAnyRole([RoleName::ADMIN]), fn($q) => $q->with('user'))
            ->with(['service'])
            ->when(! Auth::user()->hasAnyRole([RoleName::ADMIN]), function ($query) {
                $query->forCustomer();
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(isset($filters['search']), function ($query) use ($filters) {
                $query->search($filters['search']);
            })
            ->when(isset($filters['sort_by']), function ($query) use ($filters) {
                $query->sortBy($filters['sort_by'], $filters['sort_dir'] ?? 'asc');
            });

        if ($filters['paginate'] ?? false) {
            return $bookings->paginate($filters['per_page'] ?? 10);
        }

        return $bookings->get();
    }
}
