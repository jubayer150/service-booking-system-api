<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Repositories\BookingRepository;
use App\Services\BookingService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class BookingController
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(BookingRepository $bookingRepository, Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = $bookingRepository->getAll($request->query());

        return $this->responseSuccess('Bookings retrieved successfully.', [
            'bookings' => BookingResource::collection($bookings)->resource,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookingService $bookingService, BookingRequest $request): JsonResponse
    {
        Gate::authorize('create', Booking::class);

        $booking = $bookingService->create($request->validated());

        $booking->load('service');

        return $this->responseSuccess('Booking successful.', [
            'service' => BookingResource::make($booking),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        //
    }
}
