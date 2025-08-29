<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Service;
use Symfony\Component\HttpFoundation\Response;

class ServiceManagerService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        
    }

    public function update(Service $service, array $data): Service
    {
        $service->fill($data);

        if ($service->isDirty('price')) {
            //@TODO: Notify customers about price change
        }
        
        $service->save();

        return $service;
    }

    public function delete(Service $service): void
    {
        abort_if($service->bookings()->exists(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Cannot delete service with existing bookings.');

        $service->delete();
    }
}
