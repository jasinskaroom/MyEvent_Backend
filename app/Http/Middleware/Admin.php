<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\Event;

class Admin
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
        if (Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isManager())) {
                 return $next($request);
            
        }

        return redirect('login');
    }
}
