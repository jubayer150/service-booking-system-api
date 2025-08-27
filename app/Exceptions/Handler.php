<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $exception) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception): HttpResponse|JsonResponse
    {
        // Handle API requests specifically
        if ($request->is('api/*') || $request->expectsJson()) {
            return $this->handleApiException($exception, $request);
        }

        return parent::render($request, $exception);
    }

    /**
     * Handle API exceptions and return JSON responses.
     */
    private function handleApiException(Throwable $exception, Request $request): JsonResponse
    {
        $details = $this->getExceptionDetails($exception);

        $response = [
            'success' => false,
            'message' => $details['message'],
        ];

        // Include validation errors
        if ($exception instanceof ValidationException) {
            $response['errors'] = $exception->errors();
        }

        // Add debug information
        if (config('app.debug')) {
            $response['exception_type'] = get_class($exception);
            $response['file'] = $exception->getFile();
            $response['line'] = $exception->getLine();
            $response['trace'] = collect($exception->getTrace())->take(3); // Optional: limit trace
        }

        return response()->json($response, $details['status']);
    }

    /**
     * Determine the status code and message for the exception.
     */
    private function getExceptionDetails(Throwable $exception): array
    {
        return match (true) {
            $exception instanceof ModelNotFoundException,
            $exception instanceof NotFoundHttpException => [
                'message' => $this->resolveMessage($exception, 'Data not found'),
                'status' => Response::HTTP_NOT_FOUND,
            ],
            $exception instanceof ValidationException => [
                'message' => $exception->validator->errors()->first(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ],
            $exception instanceof AuthenticationException => [
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_UNAUTHORIZED,
            ],
            $exception instanceof UnauthorizedException => [
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_UNAUTHORIZED,
            ],
            $exception instanceof AccessDeniedHttpException => [
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_FORBIDDEN,
            ],
            $exception instanceof UnprocessableEntityHttpException => [
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ],
            $exception instanceof HttpException => [
                'message' => $exception->getMessage(),
                'status' => $exception->getCode(),
            ],
            $exception instanceof RouteNotFoundException => [
                'message' => $this->resolveMessage($exception, 'Route not found'),
                'status' => Response::HTTP_NOT_FOUND,
            ],
            default => [
                'message' => $this->resolveMessage($exception, 'Something went wrong'),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ],
        };
    }

    /**
     * Return exception message depending on debug mode.
     */
    private function resolveMessage(Throwable $exception, string $fallbackMessage): string
    {
        return config('app.debug') ? $exception->getMessage() : $fallbackMessage;
    }
}
