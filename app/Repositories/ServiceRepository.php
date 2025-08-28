<?php

namespace App\Repositories;

use App\Enums\RoleName;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceRepository
{
    public function getAll(?Request $request)
    {
        $services = Service::query()
            ->when(! Auth::user()->hasAnyRole([RoleName::ADMIN]), function ($query) {
                $query->active();
            });

        if ($request) {
            $services->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->query('status'));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->query('search')}%")
                        ->orWhere('description', 'like', "%{$request->query('search')}%");
                });
            })
            ->when($request->filled('sort_by'), function ($query) use ($request) {
                $query->orderBy($request->query('sort_by'), $request->query('sort_dir', 'asc'));
            });

            if ($request->boolean('get_all')) {
                return $services->get();
            }
        }

        return $services->paginate($request->query('per_page', 10));
    }
    
    public function create(array $data): Service
    {
        return Service::create($data);
    }

    public function update(Service $service, array $data): Service
    {
        $service->update($data);
        return $service;
    }

    public function delete(Service $service): void
    {
        $service->delete();
    }
}
