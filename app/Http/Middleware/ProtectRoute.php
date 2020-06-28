<?php

namespace App\Http\Middleware;

use Closure;

class ProtectRoute
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
        
        if ($request->id != "admin" ) {        
            //var_dump($request);
            return redirect('home');
        }

        return $next($request);
    }
}
