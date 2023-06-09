<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanctumAbilitiesCheck
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$abilities
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$abilities): mixed
    {
        foreach ($abilities as $ability) {
            if (!$request->user()->tokenCan($ability)) {
                return response()->json(['message' => __('auth.unauthorized')], 401);
            }
        }
        return $next($request);
    }
}
