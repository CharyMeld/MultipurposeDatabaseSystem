<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class SessionAuth
{
    public function handle($request, Closure $next)
    {
        // Prevent redirect loop for login route
        if ($request->is('login')) {
            return $next($request);
        }

        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }

        return $next($request);
    }

}
