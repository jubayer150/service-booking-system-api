<?php

namespace App\Policies;

use App\Enums\RoleName;
use App\Enums\ServiceStatus;
use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ServicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('service.viewAll');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Service $service): bool
    {
        if (! $user->can('service.view')) {
            return false;
        }

        if ($user->hasAnyRole([RoleName::ADMIN])) {
            return true;
        }

        return $service->status === ServiceStatus::ACTIVE;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('service.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Service $service): bool
    {
        return $user->can('service.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Service $service): bool
    {
        return $user->can('service.delete');
    }
}
