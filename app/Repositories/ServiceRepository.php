<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\RoleName;
use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ServiceRepository
{
    public function getAll(array $filters): Collection|LengthAwarePaginator
    {
        $services = Service::query()
            ->when(! Auth::user()->hasAnyRole([RoleName::ADMIN]), function ($query) {
                $query->active();
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
            return $services->paginate($filters['per_page'] ?? 10);
        }

        return $services->get();
    }
    
    public function create(array $data): Service
    {
        return Service::create($data);
    }
}
