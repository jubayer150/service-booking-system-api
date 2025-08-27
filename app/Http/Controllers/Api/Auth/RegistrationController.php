<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthenticationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class RegistrationController
{
    use ApiResponse;

    /**
     * Handle the incoming request.
     */
    public function __invoke(RegistrationRequest $request, AuthenticationService $service): JsonResponse
    {
        /**
         * @var \App\Models\User $user
         */
        $user = $service->register($request->validated());

        return $this->responseSuccess('User registration successful.', [
            'user' => UserResource::make($user),
            'access_token' => $user->token,
        ], Response::HTTP_CREATED);
    }
}
