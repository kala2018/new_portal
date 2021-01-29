<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class logout
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
        if(!Auth::guard('admin')->check() && !Auth::guard('member')->check()){
            return $next($request);
        }


    }
}
