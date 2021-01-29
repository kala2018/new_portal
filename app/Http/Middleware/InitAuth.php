<?php


namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Admin\Manager;
use App\Models\Admin\SmsConfig;
use App\Models\Admin\System;

class InitAuth
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
        $result = Manager::get()->first();
        $system = System::get()->first();
        $sy = SmsConfig::get()->first();
        if(!$sy && !$system && !$result){
            return redirect('/');
        }
        return $next($request);
    }

}
