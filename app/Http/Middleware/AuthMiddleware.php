<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Models\RolePermission;
use FastRoute\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;
use Closure;
use Cache;
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
        // 用户信息初始化
        UserController::initUser($request);
        DB::enableQueryLog();

        /**
         * $uses : UserController@index
         */
        $uses = $request->route()[1]['uses'];
        $uses = substr(strrchr($uses, '\\'), 1);

        $has = Role::find(session('role_id'))->permissions()->where('uses', $uses)->first();

        if (!$has){
            // 查看是否需要登录
            $userHas = Role::where('name', 'user')->first()->permissions()->where('uses', $uses)->first();
            if ($userHas){
                session()->put('backUrl', $request->header('referer'));
                return redirect('user/login');
            }
            return redirect('403');
        }

        return $next($request);
    }

}
