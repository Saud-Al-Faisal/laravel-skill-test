<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class DivisionHeadMiddleware
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
        if (User::verifyDivisionHead()) {
            return $next($request);
        } else {
            return redirect()->back();
        }

    }
}
