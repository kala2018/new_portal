<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Support\Facades\Session;

class PublicAuth
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
        if( Session::get('isVisitor') || !Auth::guard('admin')->check() && !Auth::guard('member')->check()){
            return redirect('/'.'?notAuth=1');
        }
        return $next($request);
    }
}
