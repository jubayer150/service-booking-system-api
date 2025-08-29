<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Repositories\ServiceRepository;
use App\Services\ServiceManagerService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class ServiceController
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(ServiceRepository $serviceRepository, Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Service::class);

        $services = $serviceRepository->getAll($request->query());

        return $this->responseSuccess(data: [
            'services' => ServiceResource::collection($services)->resource,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRepository $serviceRepository, ServiceRequest $request): JsonResponse
    {
        Gate::authorize('create', Service::class);

        $service = $serviceRepository->create($request->validated());

        return $this->responseSuccess('Service created successfully.', [
            'service' => ServiceResource::make($service),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceManagerService $serviceManagerService, ServiceRequest $request, Service $service): JsonResponse
    {
        Gate::authorize('update', $service);

        $service = $serviceManagerService->update($service, $request->validated());

        return $this->responseSuccess('Service updated successfully.', [
            'service' => ServiceResource::make($service),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceManagerService $serviceManagerService, Service $service): JsonResponse
    {
        Gate::authorize('delete', $service);

        $serviceManagerService->delete($service);

        return $this->responseSuccess('Service deleted successfully.');
    }
}
