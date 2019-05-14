<?php

namespace App\Http\Middleware;

use Closure;

class auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\App\users::cur()) {
            return $next($request);
        } else {
            return response([
                "message" => "Unauthorized user"
            ], 401);
        }
    }
}
