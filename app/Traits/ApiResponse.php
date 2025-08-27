<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    protected function responseSuccess(string $message = '', array $data = [], int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json(array_filter([
            'success' => true,
            'message' => $message ?: null,
            'data' => $data ?: null,
        ]), $statusCode);
    }
}