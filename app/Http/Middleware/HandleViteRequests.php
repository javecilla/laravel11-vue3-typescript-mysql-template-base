<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleViteRequests {
    public function handle(Request $request, Closure $next) {
        // Check if the app is running locally and the request is for Vite
        if (app()->environment('local') && $request->is('vite/*')) {
            return $this->proxyViteRequest($request);
        }

        return $next($request);
    }

    private function proxyViteRequest(Request $request)
    {
        // Retrieve the Vite Dev Server URL from the .env file
        $viteDevServer = env('FRONTEND_URL', 'http://localhost:5173'); // Default to localhost:5173 if FRONTEND_URL is not set

        // Initialize a cURL session for the Vite dev server
        $ch = curl_init($viteDevServer . $request->getPathInfo());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // Use Accept header from the request and default to 'application/javascript' if not provided
        $acceptHeader = $request->header('Accept', 'application/javascript');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: ' . $acceptHeader,
        ]);

        // Execute the cURL request and get the response
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get the HTTP status code
        curl_close($ch);

        // If the cURL request fails or the Vite dev server returns an error, return a JSON error response
        if ($response === false || $statusCode >= 400) {
            return Response::error('Failed to proxy request to Vite dev server', HttpResponse::HTTP_BAD_GATEWAY);
        }

        // Otherwise, return the raw response from the Vite dev server
        return Response::success($response, $statusCode)
            ->header('Content-Type', env('VITE_CONTENT_TYPE', 'application/javascript')); // Use env for content type, default to JS
    }

}
