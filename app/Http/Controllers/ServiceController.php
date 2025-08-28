<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Repositories\ServiceRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class ServiceController
{
    use ApiResponse;

    public function __construct(private ServiceRepository $serviceRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Service::class);

        $services = $this->serviceRepository->getAll($request);

        return $this->responseSuccess(data: [
            'services' => ServiceResource::collection($services)->resource,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request): JsonResponse
    {
        Gate::authorize('create', Service::class);

        $service = $this->serviceRepository->create($request->validated());

        return $this->responseSuccess('Service created successfully.', [
            'service' => ServiceResource::make($service),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service): JsonResponse
    {
        Gate::authorize('view', $service);

        return $this->responseSuccess(data:[
            'service' => ServiceResource::make($service),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, Service $service): JsonResponse
    {
        Gate::authorize('update', $service);

        $service = $this->serviceRepository->update($service, $request->validated());

        return $this->responseSuccess('Service updated successfully.', [
            'service' => ServiceResource::make($service),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service): JsonResponse
    {
        Gate::authorize('delete', $service);

        abort_if($service->bookings()->exists(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Cannot delete service with existing bookings.');

        $this->serviceRepository->delete($service);

        return $this->responseSuccess('Service deleted successfully.');
    }
}
