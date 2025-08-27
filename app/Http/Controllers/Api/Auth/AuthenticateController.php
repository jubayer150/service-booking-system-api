<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthenticationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class AuthenticateController
{
    use ApiResponse;

    public function __construct(private AuthenticationService $service)
    {
    }

    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = Auth::user();

        return $this->responseSuccess('Login successful', [
            'user' => UserResource::make($user),
            'access_token' => $this->service->createAccessToken($user),
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {

        return $this->responseSuccess($this->service->logout($request->boolean('revoke_all_tokens')), statusCode: Response::HTTP_NO_CONTENT);
    }
}
