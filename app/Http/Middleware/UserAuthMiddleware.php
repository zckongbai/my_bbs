<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/23
 * Time: 下午3:43
 */

namespace app\Http\Middleware;

use App\Http\Controllers\UserController;
use App\Models\User;
use Closure;
use Log;

class UserAuthMiddleware
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

        // 用户登录判断
        if (!UserController::checkUserIsLogin()){
            session()->put('backUrl', $request->header('referer'));
            return redirect('user/login');
        }

        // 获取 uses 控制器@方法
        $uses = $this->getUses($request);

        Log::info(
            'user auth handle',
            ['uses' => $uses, 'user_id' => session('user_id'), 'ip' => $request->getClientIp()]
        );

        // 检查权限
        if (!$this->checkPermission($uses)){
            return redirect('404');
        }
        return $next($request);
    }

    /**
     * 获取 uses 控制器@方法
     * @param $request
     * @return bool|string  XyzController@index
     */
    protected function getUses($request)
    {
        $uses = $request->route()[1]['uses'];
        $uses = substr(strrchr($uses, '\\'), 1);
        return $uses;
    }

    /**
     * 检查角色权限
     */
    protected function checkPermission($uses)
    {
        return User::find(session('user_id'))->checkPermissionByUses($uses);
    }


}
