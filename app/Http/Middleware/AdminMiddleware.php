<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class AdminMiddleware
{

    public function handle($request, Closure $next)
    {
        if (User::verifyAdmin()) {
            return $next($request);
        }
        return redirect('/login');
    }
}
