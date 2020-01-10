<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Config;

class CheckToken
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
        $token = $request->header("Token");
        $appToken = env("FSCS_EXECUTOR_APP_TOKEN");

        if($token === $appToken){
            return $next($request);
        } else {
            throw new AuthorizationException();
        }
    }
}
