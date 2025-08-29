<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Service;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ServiceManagerService
{
    public function update(Service $service, array $data): Service
    {
        $service->fill($data);

        if ($service->isDirty('price')) {
            //@TODO: Notify customers who have pending service about price change
        }
        
        $service->save();

        return $service;
    }

    public function delete(Service $service): void
    {
        throw_if(
        $service->bookings()->exists(),
        new UnprocessableEntityHttpException('Cannot delete service with existing bookings.')
        );

        $service->delete();
    }
}
