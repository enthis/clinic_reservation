<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException; // Import AuthorizationException
use Illuminate\Http\JsonResponse; // Import JsonResponse
use Illuminate\Validation\ValidationException; // Import ValidationException
use Symfony\Component\HttpKernel\Exception\HttpException; // Import HttpException

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
        $this->reportable(function (Throwable $e) {
            //
        });

        // Custom rendering for API validation errors
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return new JsonResponse([
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // Custom rendering for other HTTP exceptions (e.g., 404, 405)
        $this->renderable(function (HttpException $e, $request) {
            if ($request->expectsJson()) {
                return new JsonResponse([
                    'message' => $e->getMessage() ?: 'An error occurred.',
                ], $e->getStatusCode());
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        // Handle AuthorizationException (403 Forbidden) specifically for API/JSON requests
        if ($exception instanceof AuthorizationException && $request->expectsJson()) {
            return new JsonResponse([
                'message' => $exception->getMessage() ?: 'This action is unauthorized.'
            ], 403);
        }

        // The default render method handles other exceptions if not caught by specific renderables
        return parent::render($request, $exception);
    }
}
