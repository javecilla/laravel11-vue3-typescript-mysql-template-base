<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class EnsureTokenIsValid {

	public function handle(Request $request, Closure $next) {
		if (Auth::check()) {
			$user = Auth::user();
			$token = $request->cookie('jwt');

			if (!$user->hasToken($token)) {
				return Response::error('Unauthorized', HttpResponse::HTTP_UNAUTHORIZED);
			}
		}

		return $next($request);
	}
}
