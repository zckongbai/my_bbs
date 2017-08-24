<?php

namespace App\Http\Middleware;

use App\Models\Role;
use FastRoute\Route;
use App\Http\Controllers\UserController;
use App\Events\InitUserEvent;
use Closure;
use Log;

class AuthMiddleware
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
        // do something

        return $next($request);
    }



}
