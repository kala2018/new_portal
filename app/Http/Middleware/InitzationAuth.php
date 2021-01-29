<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Admin\Manager;

class InitzationAuth
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
        if(!Manager::get()->first()){
            return view('admin.error.index');
        }

        return $next($request);
    }
}
