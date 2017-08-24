<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/23
 * Time: 下午12:22
 */

namespace app\Http\Middleware;

use App\Http\Controllers\UserController;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\DB;

class AdminAuthMiddleware
{

    public function handle($request, Closure $next)
    {
        // DB::enableQueryLog();

        // 先检查登录
        if (!UserController::checkUserIsLogin()){
            return redirect('admin/login');
        }

        $uses = $this->getUses($request);

        // 检查权限
        if (!$this->checkPermission($uses)){
            return redirect('admin/login');
        }
        // var_dump(DB::getQueryLog());

        return $next($request);
    }

    /**
     * 获取 uses 控制器@方法
     * @param $request
     * @return bool|string
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