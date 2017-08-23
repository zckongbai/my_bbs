<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/21
 * Time: 下午9:25
 */

namespace App\Listeners;

use App\Models\User;
use App\Events\UserLoginEvent;
use Log;

class UserEventListener
{

    public function __construct()
    {
    }

    public function handle(UserLoginEvent $event)
    {
        echo "this is UserEventListener";
    }

    public function subscribe($events)
    {
        // 初始化游客
        $events->listen(
            'App\Events\InitUserEvent',
            'App\Listeners\UserEventListener@onInitUser'
        );
        // 登录事件
        $events->listen(
            'App\Events\UserLoginEvent',
            'App\Listeners\UserEventListener@onUserLogin'
        );
        // 注册事件
        $events->listen(
            'App\Events\UserRegisterEvent',
            'App\Listeners\UserEventListener@onUserRegister'
        );
        // 退出事件
        $events->listen(
            'App\Events\UserLogoutEvent',
            'App\Listeners\UserEventListener@onUserLogout'
        );

    }

    /**
     * 访问者信息初始化
     * @param $event
     */
    public function onInitUser($event)
    {
        if (!session('is_login') && !session('role_name')){
            $this->cacheUserInfo();
        }
    }

    /**
     * 处理用户登录
     */
    public function onUserLogin($event)
    {
        // 缓存用户信息
        $this->cacheUserInfo($event->user->id);
    }

    /**
     * 用户登录事件
     * @param $event
     */
    public function onUserRegister($event)
    {
        $this->cacheUserInfo($event->user->id);
    }

    /**
     * 退出
     * @param $event
     */
    public function onUserLogout($event)
    {
        $this->clearUserInfoCache();
    }

    /**
     * 缓存用户信息
     * @param string $id
     * @param string $roleName
     * @return bool
     */
    protected function cacheUserInfo($id = '', $roleName = User::VISITOR_NAME)
    {
        if ($id){
            $user = User::find($id);
            $data =  [
                'is_login' => true,
                'name'  =>$user->name,
                'role_id' => $user->role_id,
                'role_name' => $user->role->name,
                'uid' => $user->id,
            ];

        } else {
            $role = \App\Models\Role::where('name', $roleName)->first();
            $data =  [
                'is_login' => false,
                'name'  => '',
                'role_id' => $role->id,
                'role_name' => $role->name,
                'uid' => '',
            ];
        }
        setcookie('session', session_id(), 7*24*3600);
        session()->put($data);
        return true;
    }

    protected function clearUserInfoCache()
    {
        setcookie('session', session_id(), time()-1);
        session()->flush();
    }

}