<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AttachTokenToHeaders {
	public function handle(Request $request, Closure $next) {
		if ($request->hasCookie('jwt')) {
			$token = $request->cookie('jwt');
			$request->headers->set('Authorization', 'Bearer ' . $token);
		}

		return $next($request);
	}
}
