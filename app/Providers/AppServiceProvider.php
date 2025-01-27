<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * This method is used to bind services and utilities into the container.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * This method is used to configure services that should be loaded when the app starts.
     */
    public function boot(): void
    {
        // Allow the data schema to create freely database table
        // Default string length for older MySQL versions
        Schema::defaultStringLength(191);

        // Format the return data in resource collection
        // Disable wrapping of JSON resource responses
        JsonResource::withoutWrapping();
        
        // Configure API rate limiting for SPAs
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60) // Limit to 60 requests per minute
                ->by($request->user()?->id ?: $request->ip()) // Rate limit by user ID or IP
                ->response(function (Request $request, array $headers) {
                    // Return a standardized JSON response for rate-limited requests
                    return response()->json([
                        'message' => 'Too many requests. Please try again later.',
                        'status_code' => HttpResponse::HTTP_TOO_MANY_REQUESTS,
                    ], HttpResponse::HTTP_TOO_MANY_REQUESTS, $headers);
                });
        });

        // Macro for success responses
        Response::macro('success', function ($data = null, string $message = 'Request successful', int $statusCode = HttpResponse::HTTP_OK) {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => $data,
                'status_code' => $statusCode,
            ], $statusCode);
        });

         // Macro for error responses
         Response::macro('error', function (string $message, int $statusCode = HttpResponse::HTTP_BAD_REQUEST, $errors = null) {
            return Response::json([
                'success' => false,
                'message' => $message,
                'errors' => $errors,
                'status_code' => $statusCode,
            ], $statusCode);
        });


        // Macro for validation error responses
        Response::macro('validationError', function ($errors) {
            return Response::error(
                'Validation failed',
                HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                $errors
            );
        });

        // Macro for paginated responses
        Response::macro('paginate', function ($paginator) {
            return Response::json([
                'success' => true,
                'data' => $paginator->items(),
                'pagination' => [
                    'total' => $paginator->total(),
                    'count' => $paginator->count(),
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'total_pages' => $paginator->lastPage(),
                ],
            ]);
        });

        
    }
}
