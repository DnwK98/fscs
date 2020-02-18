<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header("Token");
        $appToken = env("FSCS_EXECUTOR_APP_TOKEN");

        if($token === $appToken){
            return $next($request);
        } else {
            throw new AuthorizationException();
        }
    }
}
