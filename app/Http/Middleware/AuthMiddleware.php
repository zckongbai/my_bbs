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
        $before = new BeforeMiddleware();
        $before->handle($request, $next);

        // 用户信息初始化事件
        // UserController::initUser($request);
        // 改为事件
        if (!session('role_id')){
            event(new InitUserEvent());
        }

        /**
         * $uses : UserController@index
         */
        $uses = $request->route()[1]['uses'];
        $uses = substr(strrchr($uses, '\\'), 1);

        Log::info('auth handle', ['uses' => $uses, 'role_id' => session('role_id'), 'ip' => $request->getClientIp()]);

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
